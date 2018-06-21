<?php

namespace SmartDevs\ElastiCommerce;

use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GlobalAggregation;
use Elastica\Aggregation\Histogram;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Terms as TermsQuery;
use SmartDevs\ElastiCommerce\Common\Connection;
use SmartDevs\ElastiCommerce\Config\IndexConfig;
use SmartDevs\ElastiCommerce\Config\ServerConfig;
use SmartDevs\ElastiCommerce\Util\Data\DataCollection;
use SmartDevs\ElastiCommerce\Util\Data\DataObject;

/**
 * Class Query
 * @property  indexConfig
 * @package SmartDevs\ElastiCommerce
 */
class Query
{
    /**
     * @var ServerConfig
     */
    private $serverConfig;

    /**
     * @var IndexConfig
     */
    private $indexConfig;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var mixed
     */
    private $facetCollection;

    /**
     * @var array
     */
    protected $_filter = [];

    /**
     * @var bool
     */
    private $_isLoaded = false;

    /**
     * @var null | array
     */
    private $_result = null;

    /**
     * @var BoolQuery
     */
    private $_query;

    /**
     * @var integer
     */
    private $_limit = 10000;

    /**
     * @var integer
     */
    private $_offset = 0;

    /**
     * @var array
     */
    private $_sort = [];

    /**
     * @var integer
     */
    private $_groupId = 0;

    /**
     * Indexer constructor.
     *
     * @param ServerConfig $serverConfig
     * @param IndexConfig $indexConfig
     */
    public function __construct(ServerConfig $serverConfig, IndexConfig $indexConfig)
    {
        $this->setServerConfig($serverConfig);
        $this->setIndexConfig($indexConfig);
        $this->facetCollection = new DataCollection();
        $this->_query = new BoolQuery();
    }

    /**
     * get current index name
     *
     * @return string
     */
    protected function getIndexName(): string
    {
        if (null === $this->indexName) {
            $this->indexName = $this->getIndexConfig()->getIndexAlias();
        }
        return $this->indexName;
    }

    /**
     * get connection to elasticsearch
     *
     * @return \Elasticsearch\Client
     */
    protected function getConnection()
    {
        if (null === $this->connection) {
            $connectionManager = new Connection($this->getServerConfig());
            $this->connection = $connectionManager->getConnection();
        }
        return $this->connection;
    }

    /**
     * @return ServerConfig
     */
    public function getServerConfig(): ServerConfig
    {
        return $this->serverConfig;
    }

    /**
     * @param ServerConfig $serverConfig
     * @return Query
     */
    public function setServerConfig(ServerConfig $serverConfig): Query
    {
        $this->serverConfig = $serverConfig;
        return $this;
    }

    /**
     * @return IndexConfig
     */
    public function getIndexConfig(): IndexConfig
    {
        return $this->indexConfig;
    }

    /**
     * @param IndexConfig $indexConfig
     * @return Query
     */
    public function setIndexConfig(IndexConfig $indexConfig): Query
    {
        $this->indexConfig = $indexConfig;
        return $this;
    }

    /**
     * @param $limit
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    /**
     * @param $offset
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    /**
     * @param $groupId
     */
    public function setGroupId($groupId)
    {
        $this->_groupId = $groupId;
    }

    /**
     * @return DataCollection
     * @throws \Exception
     */
    public function getResult()
    {

        $resultCollection = new DataCollection();
        foreach ($this->_result['hits']['hits'] as $entry) {
            $resultObject = new DataObject();
            $resultObject->addData($entry['_source']);
            $resultCollection->addItem($resultObject);
        }

        return $resultCollection;
    }

    /**
     * @return integer
     */
    public function getTotalHits()
    {
        return $this->_result['hits']['total'];
    }

    /**
     *
     * @throws \Exception
     */
    public function getFacets()
    {
        return $this->facetCollection;
    }

    /**
     * @return Nested
     */
    public function getNumericFacets($filter)
    {
        $nameAgg = new Terms('facet_name');
        $nameAgg->setField('filter_numeric.name');
        $nameAgg->setSize(10000);

        $valueAgg = new Terms('facet_value');
        $valueAgg->setField('filter_numeric.value');
        $valueAgg->setSize(10000);

        $nameAgg->addAggregation($valueAgg);

        $numericAgg = new Nested('facets_numeric', 'filter_numeric');
        $numericAgg->addAggregation($nameAgg);

        $globalNumericAgg = new GlobalAggregation('facets_numeric');
        $globalNumericAgg->addAggregation($numericAgg);


        $rearrangedFilter = [];

        if(is_iterable($filter['bool'])) {
            foreach ($filter['bool']['must'] as $entry) {
                $rearrangedFilter[] = $entry;
            }
        }
        $globalNumericAgg = [
            'filter' => $filter,
            'aggs' => [
                'facets_numeric' => [
                    'nested' => [
                        'path' => 'filter_numeric',
                    ],
                    'aggs' => [
                        'facet_name' => [
                            'terms' => [
                                'field' => 'filter_numeric.name',
                                'size' => 10000
                            ],
                            'aggs' => [
                                'facet_value' => [
                                    'terms' => [
                                        'field' => 'filter_numeric.value',
                                        'size' => 10000
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $globalNumericAgg;
    }

    /**
     * @return Nested
     */
    private function getStringFacets($filter)
    {
        $nameAgg = new Terms('facet_name');
        $nameAgg->setField('filter_string.name');
        $nameAgg->setSize(10000);

        $valueAgg = new Terms('facet_value');
        $valueAgg->setField('filter_string.value');
        $valueAgg->setSize(10000);

        $nameAgg->addAggregation($valueAgg);

        $stringAgg = new Nested('facets_string', 'filter_string');
        $stringAgg->addAggregation($nameAgg);

        $globalStringAgg = new GlobalAggregation('facets_string');
        $globalStringAgg->addAggregation($stringAgg);

        $globalStringAgg = [
            'filter' => $filter,
            'aggs' => [
                'facets_string' => [
                    'nested' => [
                        'path' => 'filter_string'
                    ],
                    'aggs' => [
                        'facet_name' => [
                            'terms' => [
                                'field' => 'filter_string.name',
                                'size' => 10000
                            ],
                            'aggs' => [
                                'facet_value' => [
                                    'terms' => [
                                        'field' => 'filter_string.value',
                                        'size' => 10000
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $globalStringAgg;
    }

    /**
     * @return Nested
     */
    private function getDateFacets($filter)
    {
        $nameAgg = new Terms('facet_name');
        $nameAgg->setField('filter_date.name');
        $nameAgg->setSize(10000);

        $valueAgg = new Terms('facet_value');
        $valueAgg->setField('filter_date.value');
        $valueAgg->setSize(10000);

        $nameAgg->addAggregation($valueAgg);

        $dateAgg = new Nested('facets_date', 'filter_date');
        $dateAgg->addAggregation($nameAgg);

        $globalDateAgg = new GlobalAggregation('facets_date');
        $globalDateAgg->addAggregation($dateAgg);

        $globalDateAgg = [
            'filter' => $filter,
            'aggs' => [
                'facets_date' => [
                    'nested' => [
                        'path' => 'filter_date'
                    ],
                    'aggs' => [
                        'facet_name' => [
                            'terms' => [
                                'field' => 'filter_date.name',
                                'size' => 10000
                            ],
                            'aggs' => [
                                'facet_value' => [
                                    'terms' => [
                                        'field' => 'filter_date.value',
                                        'size' => 10000
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $globalDateAgg;
    }

    /**
     * @param $rawFacet
     * @return DataObject
     */
    private function createFacet($rawFacet): DataObject
    {
        $facet = new DataObject();
        $facet->setId($rawFacet['key']);
        $facet->setCode($rawFacet['key']);
        $facet->setDocCount($rawFacet['doc_count']);
        if ($this->hasValues($rawFacet)) {
            $facet->setValues($rawFacet['facet_value']['buckets']);
        }

        return $facet;
    }

    /**
     * @param $aggregations
     * @param $type
     * @throws \Exception
     */
    private function addFacetsToCollection($aggregations, $type)
    {
        if (
            array_key_exists($type, $aggregations)
            && array_key_exists('facet_name', $aggregations[$type][$type])
            && array_key_exists('buckets', $aggregations[$type][$type]['facet_name'])
            && is_array($aggregations[$type][$type]['facet_name']['buckets'])
        ) {
            foreach ($aggregations[$type][$type]['facet_name']['buckets'] as $rawFacet) {
                $facet = $this->createFacet($rawFacet);

                if ($this->facetCollection->getItemById($facet->getId()) == null) {
                    $this->facetCollection->addItem($facet);
                }
            }
        }
    }

    /**
     * @param $fieldName
     * @param $value
     * @return $this
     */
    public function addCategoryFilter($value)
    {
        $categoryDirect = 'category_direct';
        $filter = new Term();
        $filter->setParam($categoryDirect, $value);
        $this->_filter[] = $filter;

        return $this;
    }

    /**
     * @param $fieldName
     * @param $value
     * @return $this
     */
    public function addFieldToFilter($fieldName, $value)
    {
        $filter = new \Elastica\Query\Nested();

        switch (gettype($value)) {
            case 'integer':
                $path = 'filter_numeric';
                break;
            case 'date':
                $path = 'filter_date';
                break;
            default:
                $path = 'filter_string';
                break;
        }

        $filter->setPath($path);
        $boolQuery = new BoolQuery();

        $valueQuery = new Term();
        $valueQuery->setParam($path . '.value', (integer)$value);

        $fieldQuery = new Term();
        $fieldQuery->setParam($path . '.name', (string)$fieldName);

        $boolQuery->addMust($fieldQuery);
        $boolQuery->addMust($valueQuery);

        $filter->setQuery($boolQuery);
        $this->_filter[] = $filter;

        return $this;
    }

    /**
     * @param array $visibility
     */
    public function addVisibilityFilter($visibility = [2, 4])
    {
        $termsQuery = new TermsQuery('visibility', $visibility);

        $this->_filter[] = $termsQuery;
    }

    /**
     * @param int $status
     */
    public function addStatusFilter($status = 1)
    {
        $termQuery = new Term(['stock_status' => $status]);

        $this->_filter[] = $termQuery;
    }

    /**
     * addRangeFilter
     *
     * @param string $fieldName
     * @param $min
     * @param $max
     * @return $this
     */
    public function addRangeFilter($fieldName, $min, $max)
    {
        $filter = new \Elastica\Query\Nested();

        switch (gettype($min)) {
            case 'integer':
                $path = 'filter_numeric';
                break;
            case 'date':
                $path = 'filter_date';
                break;
            default:
                $path = 'filter_string';
                break;
        }

        $filter->setPath($path);
        $rangeFilter = new \Elastica\Query\Range();
        $rangeFilter->addField($path . '.value', ['from' => $min, 'to' => $max]);
        $filter->setQuery($rangeFilter);
        $this->_filter[] = $filter;

        return $this;
    }

    public function addPriceFilter($min, $max)
    {
        $rangeFilter = new \Elastica\Query\Range('price.final_price', ['from' => $min, 'to' => $max]);
        $this->_filter[] = $rangeFilter;

        return $this;
    }

    /**
     * @return bool
     */
    public function load()
    {
        header('Content-Type: application/json');


        $query = new \Elastica\Query();
        $querry = [
            'query' => []
        ];

        $querry['sort'] = $this->_sort;

        foreach ($this->_filter as $filter) {
            $this->_query->addMust($filter);
        }

        $querry['query']['bool'] = $this->_query->toArray()['bool'];

        $query->setSort($this->_sort);

        $numericAgg = $this->getNumericFacets($querry['query']);
        $stringAgg = $this->getStringFacets($querry['query']);
        $dateAgg = $this->getDateFacets($querry['query']);

        $priceAgg = $this->getPriceFacet($this->_groupId);

        $querry['aggs']['facets_numeric'] = $numericAgg;
        #$querry['aggs']['filter'] = $querry['query'];
        $querry['aggs']['facets_string'] = $stringAgg;
        $querry['aggs']['facets_date'] = $dateAgg;
        $querry['aggs']['price'] = $priceAgg;

        #$query->addAggregation($numericAgg);
        #$query->addAggregation($stringAgg);
        #$query->addAggregation($dateAgg);
        #$query->addAggregation($priceAgg);

        $query->setQuery($this->_query);
        $query->setSize($this->_limit);
        $query->setFrom($this->_offset);

        $querry['size'] = $this->_limit;
        $querry['from'] = $this->_offset;

        $indexName = $this->getIndexName();


        #echo '<pre>';
        #print_r(json_encode($query->toArray()));
        #print_r(json_encode($querry));
        #print_r(json_encode($this->_result));
        #die();


        $connection = $this->getConnection();
        $this->_result = $connection->search(['index' => $indexName, 'type' => 'product', 'body' => json_encode($querry)]);

        #print_r(json_encode($this->_result));
        #die();


        $this->_prepareFacets();

        return true;
    }

    /**
     * execute pre-built query
     */
    public function execute()
    {
        $this->load();
    }

    /**
     *
     */
    protected function _prepareFacets()
    {
        try {
            $this->addFacetsToCollection($this->_result['aggregations'], 'facets_numeric');
            $this->addFacetsToCollection($this->_result['aggregations'], 'facets_string');
            $this->addFacetsToCollection($this->_result['aggregations'], 'facets_date');
            $this->addPriceFacetsToCollection($this->_result['aggregations'], 'price');
        } catch (\Exception $e) {
            // just do nothing...
        }
    }

    /**
     * @param $field
     * @param string $direction
     * @param string $type
     *
     * @return $this
     */
    public function addOrder($field, $direction = 'asc', $type = 'sort_string')
    {
        $this->_sort[] = [
            $type . '.' . $field => [
                'order' => $direction
            ]
        ];

        return $this;
    }

    /**
     * setQueryString
     *
     * @param string $queryString
     * @return $this
     */
    public function setQueryString($queryString)
    {
        $multiMatch = new MultiMatch();
        $multiMatch->setFields(['name^2', 'sku', 'short_description^2', 'fulltext_boosted', 'fulltext', 'completion']);
        $multiMatch->setOperator('AND');
        $multiMatch->setType('cross_fields');
        $multiMatch->setQuery($queryString);

        $this->_filter[] = $multiMatch;

        return $this;
    }

    /**
     * addCustomFilter
     *
     * @param AbstractQuery $query
     */
    public function addCustomFilter(AbstractQuery $query)
    {
        $this->_filter[] = $query;
        return $this;
    }

    /**
     * @param $queryString
     * @param array $attributSetFilter
     * @return array
     */
    public function suggest($queryString, $attributSetFilter = [])
    {
        $indexName = $this->getIndexName();

        $filter = [];
        foreach ($attributSetFilter as $attributeId) {
            $filter[] = [
               'term' => [
                    'attribute_set_id' => $attributeId
                ]
            ];
        }
        $filter[] = [
            'terms' => [
                'visibility' => [
                    "2", "4"
                ]
            ],
        ];

        $body = [
            "_source" => [
                "includes" => [
                    "result.name",
                    "result.url_path",
                    "result.attribute_set_id"
                ]
            ],
            'query' => [
                'bool' => [
                    'filter' => $filter,
                    'must' => [
                    ],
                ],
            ]
        ];

            $body['query']['bool']['must'][] = [
                'multi_match' => [
                    'fields' => [
                        'name',
                        'fulltext',
                        'fulltext_boosted'
                    ],
                    'operator' => 'AND',
                    'type' => 'best_fields',
                    'query' => "$queryString",
                    'fuzziness' => 'AUTO',
                    "zero_terms_query" => "all"
                ],
            ];

        $result = $this->getConnection()->search(
            [
                'index' => $indexName,
                'body' => json_encode($body)
            ]
        );

        return $result;
    }

    /**
     * @param int $customerGroupId
     * @param int $interval
     * @return Histogram
     */
    private function getPriceFacet($customerGroupId = 0, $interval = 1)
    {
        $priceAgg = new Histogram(
            'price',
            'price.final_price',
            $interval
        );
        $priceAgg->setMinimumDocumentCount(1);

        $priceAgg = [
            'histogram' => [
                'field' => 'price.final_price',
                'interval' => 1,
                'min_doc_count' => 1
            ]
        ];

        return $priceAgg;
    }

    /**
     * @param $aggregations
     * @param $type
     * @throws \Exception
     */
    private function addPriceFacetsToCollection($aggregations, $type)
    {
        $facet = new DataObject();
        $facet->setId($type);
        $facet->setCode($type);
        $facet->setDocCount(0);

        if (
            array_key_exists($type, $aggregations)
            && array_key_exists('buckets', $aggregations[$type])
        ) {
            $facet->setValues($aggregations[$type]['buckets']);
        }

        if ($this->facetCollection->getItemById($facet->getId()) == null) {
            $this->facetCollection->addItem($facet);
        }
    }

    /**
     * @param $rawFacet
     * @return bool
     */
    private function hasValues($rawFacet)
    {
        return array_key_exists('facet_value', $rawFacet) && array_key_exists('buckets', $rawFacet['facet_value']);
    }
}
