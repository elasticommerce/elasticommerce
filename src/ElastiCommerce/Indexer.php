<?php

namespace SmartDevs\ElastiCommerce;

use SmartDevs\ElastiCommerce\Common\Connection;
use SmartDevs\ElastiCommerce\Implementor\Config;
use SmartDevs\ElastiCommerce\Index\Settings;
use SmartDevs\ElastiCommerce\Index\Mappings;
use SmartDevs\ElastiCommerce\Index\BulkCollection;

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
     * @var Index
     */
    protected $index = null;

    /**
     * @var Mappings
     */
    protected $mappings = null;

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
        $this->mappings = new Mappings($config);
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
     * @return Index
     */
    protected function getIndex(): Index
    {
        if (null === $this->index) {
            $this->index = new Index($this->getConnection()->indices());
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
     * @return Mappings
     */
    public function getMappings(): Mappings
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

    public function createIndex()
    {
        $alias = $this->getConfig()->getIndexConfig()->getIndexAlias();
        $this->getIndex()->create(
            $alias,
            $this->getConfig()->getIndexConfig()->getNumberOfShards(),
            $this->getConfig()->getIndexConfig()->getNumberOfReplicas());
    }
}