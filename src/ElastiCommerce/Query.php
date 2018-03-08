<?php

namespace SmartDevs\ElastiCommerce;

use Elastica\Aggregation\Histogram;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
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
    public function getNumericFacets(): Nested
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
        return $numericAgg;
    }

    /**
     * @return Nested
     */
    private function getStringFacets()
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

        return $stringAgg;
    }

    /**
     * @return Nested
     */
    private function getDateFacets()
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
        return $dateAgg;
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
            && array_key_exists('facet_name', $aggregations[$type])
            && array_key_exists('buckets', $aggregations[$type]['facet_name'])
            && is_array($aggregations[$type]['facet_name']['buckets'])
        ) {
            foreach ($aggregations[$type]['facet_name']['buckets'] as $rawFacet) {
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
        $rangeFilter->addField($path.'.value',[ 'from' => $min, 'to' => $max]);
        $filter->setQuery($rangeFilter);
        $this->_filter[] = $filter;

        return $this;
    }

    /**
     * @return bool
     */
    public function load()
    {
        $query = new \Elastica\Query();

        foreach ($this->_filter as $filter) {
            $this->_query->addMust($filter);
        }

        $query->setSort($this->_sort);

        $numericAgg = $this->getNumericFacets();
        $stringAgg = $this->getStringFacets();
        $dateAgg = $this->getDateFacets();

        $priceAgg = $this->getPriceFacet($this->_groupId);

        $query->addAggregation($numericAgg);
        $query->addAggregation($stringAgg);
        $query->addAggregation($dateAgg);
        $query->addAggregation($priceAgg);


        $query->setQuery($this->_query);
        $query->setSize($this->_limit);
        $query->setFrom($this->_offset);

        $indexName = $this->getIndexName();


        #header('Content-Type: application/json');
        #echo '<pre>';
        #print_r(json_encode($query->toArray()));
        #print_r(json_encode($this->_result));
        #die();

        $connection = $this->getConnection();
        $this->_result = $connection->search(['index' => $indexName, 'type' => 'product', 'body' => json_encode($query->toArray())]);

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
        $multiMatch->setFields(['fulltext', 'completion', 'fulltext_boosted']);
        $multiMatch->setOperator('OR');
        $multiMatch->setType('cross_fields');
        $multiMatch->setQuery($queryString);

        $this->_filter[] = $multiMatch;

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
                ]
            ;
        }
        $filter[] = [
            'terms' => [
                'visibility' => [
                    2, 4
                ]
            ],
        ];

        $body = json_encode(
            [
                "_source" => [
                    "includes" => [
                        "result.name",
                        "result.url_path",
                        "result.attribute_set_id"
                    ]
                ],
                'query' => [
                    'bool' => [
                        'filter' => [
                            'bool' => [
                                'must' => $filter,
                            ]
                        ],
                        'must' => [
                            'multi_match' => [
                                'fields' => [
                                    'fulltext',
                                    'completion',
                                    'fulltext_boosted'
                                ],
                                'operator' => 'OR',
                                'type' => 'cross_fields',
                                'query' => "$queryString"
                            ],
                        ],
                    ],
                ],
                /*'suggest' => [
                    'completion' => [
                        'text' => "$queryString",
                        'term' => [
                            'field' => 'completion'
                        ]
                    ],
                    'fulltext' => [
                        'text' => "$queryString",
                        'term' => [
                            'field' => 'fulltext'
                        ]
                    ],
                    'fulltext_boosted' => [
                        'text' => "$queryString",
                        'term' => [
                            'field' => 'fulltext_boosted'
                        ]
                    ]
                ]*/
            ]
        );


        $result = $this->getConnection()->search(
            [
                'index' => $indexName,
                'body' => $body
            ]
        );

        header('Content-Type: application/json');
        #print_r($body);
        print_r(json_encode($result));
        die();

        return $result;
    }

    /**
     * @param int $customerGroupId
     * @param int $interval
     * @return Histogram
     */
    private function getPriceFacet($customerGroupId = 0, $interval = 10)
    {
        $priceAgg = new Histogram(
            'price',
            'price.final_price_customer_group_' . $customerGroupId,
            $interval
        );
        $priceAgg->setMinimumDocumentCount(1);

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
