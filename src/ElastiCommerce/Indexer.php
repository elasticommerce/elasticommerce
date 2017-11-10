<?php

namespace SmartDevs\ElastiCommerce;

use SmartDevs\ElastiCommerce\Common\Connection;
use SmartDevs\ElastiCommerce\Config\IndexConfig;
use SmartDevs\ElastiCommerce\Config\ServerConfig;
use SmartDevs\ElastiCommerce\Facades\IndexFacade;
use SmartDevs\ElastiCommerce\Implementor\Config;
use SmartDevs\ElastiCommerce\Implementor\Index\TypeCollectionImplementor;
use SmartDevs\ElastiCommerce\Index\BulkCollection;
use SmartDevs\ElastiCommerce\Index\Settings;
use SmartDevs\ElastiCommerce\Index\Type;
use SmartDevs\ElastiCommerce\Index\Type\Mapping;
use SmartDevs\ElastiCommerce\Index\TypeCollection;

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
     * @var ServerConfig
     */
    protected $serverConfig = null;

    /**
     * @var IndexConfig
     */
    protected $indexConfig = null;

    /**
     * @var IndexFacade
     */
    protected $index = null;

    /**
     * @var TypeCollectionImplementor
     */
    protected $indexTypes = null;

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
     * @return Indexer
     */
    public function setServerConfig(ServerConfig $serverConfig): Indexer
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
     * @return Indexer
     */
    public function setIndexConfig(IndexConfig $indexConfig): Indexer
    {
        $this->indexConfig = $indexConfig;
        return $this;
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
     * get manager for index metadata operations
     *
     * @return IndexFacade
     */
    protected function getIndex(): IndexFacade
    {
        if (null === $this->index) {
            $this->index = new IndexFacade($this->getConnection()->indices());
        }
        return $this->index;
    }

    /**
     * @return TypeCollectionImplementor|TypeCollection
     */
    protected function getIndexTypes(): TypeCollectionImplementor
    {
        if (null === $this->indexTypes) {
            $this->indexTypes = new TypeCollection();
        }
        return $this->indexTypes;
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
        if (null === $this->mapping) {
            $this->mapping = new Mapping($this->getIndexConfig());
        }
        return $this->mapping;
    }

    /**
     * @return Settings
     */
    public function getIndexSettings(): Settings
    {
        if (null === $this->settings) {
            $this->settings = new Settings($this->getIndexConfig());
        }
        return $this->settings;
    }

    /**
     * @return BulkCollection
     */
    public function getBulk()
    {
        if (null === $this->bulkCollection) {
            $this->bulkCollection = new BulkCollection($this->getIndexConfig());
        }
        return $this->bulkCollection;
    }

    public function sendBulk()
    {
        $params = [];
        $indexName = $this->getIndexName();
        $this->getBulk()->walk(function ($item) use (&$params, $indexName) {
            $params = array_merge($params, $item->getBulkArray($indexName));
        });

        #$this->getConnection()->bulk(['body' => $params]);
        $this->getBulk()->clear();
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
                    $this->getIndexConfig()->getIndexAlias(),
                    time());
            } else {
                $this->indexName = $this->getIndexConfig()->getIndexAlias();
            }

        }
        return $this->indexName;
    }

    /**
     * registers a new document type to indexer
     *
     * @param $type
     * @return Indexer
     */
    public function registerDocumentType(string $name): Indexer
    {
        $type = new Type();
        $type->setName($name);
        $type->setMapping(new Mapping($this->getIndexConfig()));
        $this->getIndexTypes()->addType($type);
        return $this;
    }

    public function getTypeMapping(string $typeName)
    {
        return $this->getIndexTypes()->getItemById($typeName);
    }

    /**
     * create new index
     */
    public function createIndex()
    {
        $indexType = $this->getIndexTypes()->getFirstItem();
        $this->getIndex()->create($this->getIndexName(), $this->getIndexSettings(), $indexType->getMapping()->getDynamicTemplates()->toSchema());
        foreach ($this->getIndexTypes() as $indexType) {
            $this->getIndex()->setMapping($this->getIndexName(), $indexType->getName(), $indexType->getMapping());
        }
        #$this->getIndex()->setMapping($this->getIndexName())
    }

    /**
     * rotate index
     */
    public function rotateIndex()
    {
        $this->getIndex()->rotateAlias($this->getIndexName(), $this->getIndexConfig()->getIndexAlias());
        $this->getIndex()->deleteOrphanedIndices($this->getIndexConfig()->getIndexAlias());
    }
}