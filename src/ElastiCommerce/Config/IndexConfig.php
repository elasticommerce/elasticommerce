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
     * @var bool
     */
    protected $isInitialized = null;

    /**
     * @var Config
     */
    protected $config = null;

    /**
     * Snowball languages supported in elasticsearch
     *
     * @var array
     * @link http://www.elasticsearch.org/guide/reference/index-modules/analysis/snowball-tokenfilter.html
     */
    protected $supportedLanguages = [
        'Armenian',
        'Basque',
        'Catalan',
        'Danish',
        'Dutch',
        'English',
        'Finnish',
        'French',
        'German',
        'Hungarian',
        'Italian',
        'Kp',
        'Lovins',
        'Norwegian',
        'Porter',
        'Portuguese',
        'Romanian',
        'Russian',
        'Spanish',
        'Swedish',
        'Turkish',
    ];

    /**
     * supported language languages codes present by Snowball or default in elasticsearch or lucene
     *
     * @var array
     * @link http://www.elasticsearch.org/guide/reference/index-modules/analysis/snowball-tokenfilter.html
     */
    protected $supportedLanguageCodes = [
        /**
         * SnowBall filter based
         */
        // Danish
        'da' => 'da_DK',
        // Dutch
        'nl' => 'nl_NL',
        // English
        'en' => ['en_AU', 'en_CA', 'en_NZ', 'en_GB', 'en_US'],
        // Finnish
        'fi' => 'fi_FI',
        // French
        'fr' => ['fr_CA', 'fr_FR'],
        // German
        'de' => ['de_DE', 'de_DE', 'de_AT'],
        // Hungarian
        'hu' => 'hu_HU',
        // Italian
        'it' => ['it_IT', 'it_CH'],
        // Norwegian
        'nb' => ['nb_NO', 'nn_NO'],
        // Portuguese
        'pt' => ['pt_BR', 'pt_PT'],
        // Romanian
        'ro' => 'ro_RO',
        // Russian
        'ru' => 'ru_RU',
        // Spanish
        'es' => ['es_AR', 'es_CL', 'es_CO', 'es_CR', 'es_ES', 'es_MX', 'es_PA', 'es_PE', 'es_VE'],
        // Swedish
        'sv' => 'sv_SE',
        // Turkish
        'tr' => 'tr_TR',

        /**
         * Lucene class based
         */
        // Czech
        'cs' => 'cs_CZ',
        // Greek
        'el' => 'el_GR',
        // Thai
        'th' => 'th_TH',
        // Chinese
        'zh' => ['zh_CN', 'zh_HK', 'zh_TW'],
        // Japanese
        'ja' => 'ja_JP',
        // Korean
        'ko' => 'ko_KR'
    ];

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