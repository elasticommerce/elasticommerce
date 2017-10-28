<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Config;

abstract class IndexConfig
{
    /**
     * number of shards a index should have
     *
     * @var int
     */
    protected $numberOfShards = 5;

    /**
     * number of replicas in an index
     *
     * @var int
     */
    protected $numberOfReplicas = 1;

    /**
     * index language
     *
     * @var string
     */
    protected $language = null;

    /**
     * index language code
     *
     * @var string
     */
    protected $languageCode = null;

    /**
     * index locale code
     *
     * @var string
     */
    protected $localeCode = null;

    /**
     * index alias
     *
     * @var string
     */
    protected $indexAlias = null;

    /**
     * path to analyzer config file
     *
     * @var string
     */
    protected $analyzerConfigFile = null;

    /**
     * path to schema config file
     *
     * @var string
     */
    protected $schemaConfigFile = null;

    /**
     * @return int
     */
    public function getNumberOfShards(): int
    {
        return $this->numberOfShards;
    }

    /**
     * @param int $numberOfShards
     * @return IndexConfig
     */
    public function setNumberOfShards(int $numberOfShards): IndexConfig
    {
        $this->numberOfShards = $numberOfShards;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfReplicas(): int
    {
        return $this->numberOfReplicas;
    }

    /**
     * @param int $numberOfReplicas
     * @return IndexConfig
     */
    public function setNumberOfReplicas(int $numberOfReplicas): IndexConfig
    {
        $this->numberOfReplicas = $numberOfReplicas;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return IndexConfig
     */
    public function setLanguage(string $language): IndexConfig
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->languageCode;
    }

    /**
     * @param string $languageCode
     * @return IndexConfig
     */
    public function setLanguageCode(string $languageCode): IndexConfig
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->localeCode;
    }

    /**
     * @param string $localeCode
     * @return IndexConfig
     */
    public function setLocaleCode(string $localeCode): IndexConfig
    {
        $this->localeCode = $localeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndexAlias(): string
    {
        return $this->indexAlias;
    }

    /**
     * @param string $indexAlias
     * @return IndexConfig
     */
    public function setIndexAlias(string $indexAlias): IndexConfig
    {
        $this->indexAlias = $indexAlias;
        return $this;
    }

    /**
     * @return string
     */
    public function getAnalyzerConfigFile(): string
    {
        return $this->analyzerConfigFile;
    }

    /**
     * @param string $analyzerConfigFile
     * @return IndexConfig
     */
    public function setAnalyzerConfigFile(string $analyzerConfigFile): IndexConfig
    {
        $this->analyzerConfigFile = $analyzerConfigFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getSchemaConfigFile(): string
    {
        return $this->schemaConfigFile;
    }

    /**
     * @param string $schemaConfigFile
     * @return IndexConfig
     */
    public function setSchemaConfigFile(string $schemaConfigFile): IndexConfig
    {
        $this->schemaConfigFile = $schemaConfigFile;
        return $this;
    }
}