<?php

namespace SmartDevs\ElastiCommerce;

use SmartDevs\ElastiCommerce\Common\Connection;
use SmartDevs\ElastiCommerce\Facades\IndexFacade;
use SmartDevs\ElastiCommerce\Implementor\Config;
use SmartDevs\ElastiCommerce\Implementor\Facades\IndexFacadeImplementor;
use SmartDevs\ElastiCommerce\Index\BulkCollection;
use SmartDevs\ElastiCommerce\Index\Settings;
use SmartDevs\ElastiCommerce\Index\Type\Mapping;

class Indexer
{
    /**
     * flag if current process is a full reindex
     *
     * @var bool
     */
    protected $isFullReindex = false;

    /**
     * @var Config
     */
    protected $config = null;

    /**
     * @var IndexFacadeImplementor
     */
    protected $index = null;

    /**
     * current index name alias or tmp name for full reindex
     *
     * @var string
     */
    protected $indexName = null;

    /**
     * @var Mapping
     */
    protected $mapping = null;

    /**
     * @var Settings
     */
    protected $settings = null;

    /**
     * @var \Elasticsearch\Client
     */
    protected $connection = null;

    /**
     * @var BulkCollection
     */
    protected $bulkCollection = null;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->settings = new Settings($config);
        $this->mappings = new Mapping($config);
        $this->bulkCollection = new BulkCollection($config);
    }

    /**
     * get config instance
     *
     * @return Config
     */
    protected function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * get connection to elasticsearch
     *
     * @return \Elasticsearch\Client
     */
    protected function getConnection(): \Elasticsearch\Client
    {
        if (null === $this->connection) {
            $connectionManager = new Connection($this->config);
            $this->connection = $connectionManager->getConnection();
        }
        return $this->connection;
    }

    /**
     * get manager for index metadata operations
     *
     * @return IndexFacadeImplementor
     */
    protected function getIndex(): IndexFacadeImplementor
    {
        if (null === $this->index) {
            $this->index = new IndexFacade($this->getConnection()->indices());
        }
        return $this->index;
    }


    /**
     * set flag if we currently doing a full reindex
     *
     * @return Indexer
     */
    public function setIsFullReindex($value): Indexer
    {
        if ($value === false || $value === true) {
            $this->isFullReindex = (bool)$value;
        }
        return $this;
    }

    /**
     * get flag if we currently doing a full reindex
     *
     * @return bool
     */
    public function isFullReindex()
    {
        return $this->isFullReindex;
    }

    /**
     * @return Mapping
     */
    public function getMapping(): Mapping
    {
        return $this->mappings;
    }

    /**
     * @return Settings
     */
    public function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @return BulkCollection
     */
    public function getBulk()
    {
        return $this->bulkCollection;
    }

    /**
     * get current index name
     *
     * @return string
     */
    protected function getIndexName(): string
    {
        if (null === $this->indexName) {
            if (true === $this->isFullReindex()) {
                $this->indexName = sprintf('%s_%u',
                    $this->getConfig()->getIndexConfig()->getIndexAlias(),
                    time());
            } else {
                $this->indexName = $this->getConfig()->getIndexConfig()->getIndexAlias();
            }

        }
        return $this->indexName;
    }

    /**
     * create new index
     */
    public function createIndex()
    {
        $this->getIndex()->create(
            $this->getIndexName(),
            $this->getConfig()->getIndexConfig()->getNumberOfShards(),
            $this->getConfig()->getIndexConfig()->getNumberOfReplicas());
    }

    /**
     * rotate index
     */
    public function rotateIndex()
    {
        $this->getIndex()->rotateAlias(
            $this->getIndexName(),
            $this->getConfig()->getIndexConfig()->getIndexAlias()
        );
        $this->getIndex()->deleteOrphanedIndices($this->getConfig()->getIndexConfig()->getIndexAlias());
    }
}