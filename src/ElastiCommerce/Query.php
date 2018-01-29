<?php
namespace SmartDevs\ElastiCommerce;

use Elastica\Aggregation\Nested;
use Elastica\Aggregation\Terms;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\Term;
use SmartDevs\ElastiCommerce\Common\Connection;
use SmartDevs\ElastiCommerce\Common\Facet;
use SmartDevs\ElastiCommerce\Common\Facet\Collection;
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
     * @var Collection
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
    protected function getConnection(): \Elasticsearch\Client
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
     * @return DataCollection
     * @throws \Exception
     */
    public function getResult()
    {
        $query = new \Elastica\Query();

        $indexName = $this->getIndexName();

        /** @var \Elasticsearch\Client $connection */
        $connection = $this->getConnection();

        foreach ($this->_filter as $filter){
            $this->_query->addMust($filter);
        }

        $query->setQuery($this->_query);
        $query->setSize(10000);

        $result = $connection->search(['index' => $indexName, 'type' => 'product', 'body' => json_encode($query->toArray())]);

        $resultCollection = new DataCollection();
        foreach ($result['hits']['hits'] as $entry){
            $resultObject = new DataObject();
            $resultObject->addData($entry['_source']);
            $resultCollection->addItem($resultObject);
        }

        return $resultCollection;
    }

    /**
     *
     */
    public function getFacets()
    {
        $numericAgg = $this->getNumericFacets();
        $stringAgg = $this->getStringFacets();
        $dateAgg = $this->getDateFacets();

        $query = new \Elastica\Query();
        $query->addAggregation($numericAgg);
        $query->addAggregation($stringAgg);
        $query->addAggregation($dateAgg);

        foreach ($this->_filter as $filter){
            $this->_query->addMust($filter);
        }

        $query->setQuery($this->_query);
        $query->setSize(10000);

        $indexName = $this->getIndexName();

        $connection = $this->getConnection();
        $result = $connection->search(['index' => $indexName, 'type' => 'product', 'body' => json_encode($query->toArray())]);

        $this->addFacetsToCollection($result['aggregations'], 'facets_numeric');
        $this->addFacetsToCollection($result['aggregations'], 'facets_string');
        $this->addFacetsToCollection($result['aggregations'], 'facets_date');

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
     * @return Facet
     */
    private function createFacet($rawFacet): DataObject
    {
        $facet = new DataObject();
        $facet->setCode($rawFacet['key']);
        $facet->setDocCount($rawFacet['doc_count']);
        $facet->setValues($rawFacet['facet_value']['buckets']);
        return $facet;
    }

    /**
     * @param $aggregations
     * @param $type
     * @throws \Exception
     */
    private function addFacetsToCollection($aggregations, $type)
    {
        if(
            array_key_exists($type, $aggregations)
            && array_key_exists('facet_name', $aggregations[$type])
            && array_key_exists('buckets', $aggregations[$type]['facet_name'])
            && is_array($aggregations[$type]['facet_name']['buckets'])
        ) {
            foreach ($aggregations[$type]['facet_name']['buckets'] as $rawFacet) {
                $facet = $this->createFacet($rawFacet);
                $this->facetCollection->addItem($facet);
            }
        }
    }

    /**
     * @param $fieldName
     * @param $value
     * @return $this
     */
    public function addFieldToFilter($fieldName, $value)
    {
        $filter = new \Elastica\Query\Match();
        $filter->setParam($fieldName, $value);
        $this->_filter[] = $filter;

        return $this;
    }

    /**
     * @return bool
     */
    public function load()
    {
        return true;
    }
}