<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Config\IndexConfig;
use SmartDevs\ElastiCommerce\Exception;
use SmartDevs\ElastiCommerce\Index\Analysis\{
    AnalyzerCollection, CharFilterCollection, TokenFilterCollection, TokenizerCollection
};

class Settings
{
    /**
     * @var bool
     */
    protected $isInitialized = null;

    /**
     * @var IndexConfig
     */
    protected $indexConfig = null;

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
     * @var CharFilterCollection
     */
    protected $charFilter = null;

    /**
     * @var TokenizerCollection
     */
    protected $tokenizer = null;

    /**
     * @var TokenFilterCollection
     */
    protected $tokenFilter = null;

    /**
     * @var AnalyzerCollection
     */
    protected $analyzer = null;

    /**
     * Settings constructor.
     *
     * @param Config $config
     */
    public function __construct(IndexConfig $indexConfig)
    {
        $this->indexConfig = $indexConfig;
        $this->isInitialized = false;
    }


    /**
     * set flag the class is initialized (lazy loading)
     *
     * @param bool $value
     * @return Settings
     */
    protected function setIsInitialized(bool $flag): Mappings
    {
        $this->isInitialized = $flag;
    }

    /**
     * checks if class initialized (lazy loading)
     *
     * @return bool
     */
    protected function isInitialized(): bool
    {
        return $this->isInitialized;
    }

    /**
     * initialize class
     *
     * @return Settings
     */
    protected function initialize(): Settings
    {
        $this->readAnalyzerFromConfigFile();
        return $this;
    }

    /**
     * check a given file is valid and readable
     *
     * @param $filename
     * @return bool
     */
    protected function isConfigFileReadable($filename): bool
    {
        return file_exists($filename) && is_readable($filename) && false === is_dir($filename);
    }

    /**
     * init analyzer from config file
     *
     * @return $this
     * @throws \Exception
     */
    protected function readAnalyzerFromConfigFile(): Settings
    {
        $configFile = $this->indexConfig->getAnalyzerConfigFile();
        if (true === $this->isConfigFileReadable($configFile)) {
            $xml = simplexml_load_file($configFile);
        }
        if (false === isset($xml) || false === $xml instanceof \SimpleXMLElement) {
            throw new Exception('missing valid analyzer config file');
        }
        $this->initAnalyzerFromXml($xml);
        return $this;
    }

    /**
     * init all analyzer and their dependencies from xml
     *
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    protected function initAnalyzerFromXml(\SimpleXMLElement $xml): Settings
    {
        //init char filter
        $this->charFilter = new CharFilterCollection();
        if (true === property_exists($xml, 'character_filter')) {
            $this->charFilter->setXmlConfig($xml);
        }
        //init char filter
        $this->tokenizer = new TokenizerCollection();
        if (true === property_exists($xml, 'tokenizer')) {
            $this->tokenizer->setXmlConfig($xml);
        }
        //init token filter
        $this->tokenFilter = new TokenFilterCollection();
        if (true === property_exists($xml, 'token_filter')) {
            $this->tokenFilter->setXmlConfig($xml);

        }
        //init analyzer
        $this->analyzer = new AnalyzerCollection();
        if (true === property_exists($xml, 'analyzer')) {
            $this->analyzer->setXmlConfig($xml);
        }
        return $this;
    }

    /**
     * get locale code for current index
     *
     * @return string
     */
    public function getLocaleCode(): string
    {
        return $this->indexConfig->getLocaleCode();
    }

    /**
     * get language code for current index
     *
     * @return string
     */
    public function getLanguageCode(): string
    {
        return $this->indexConfig->getLanguageCode();
    }

    /**
     * get language for cirrent index
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->indexConfig->getLanguage();
    }

    /**
     * @return int
     */
    public function getNumberOfShards(): int
    {
        return $this->indexConfig->getNumberOfShards();
    }

    /**
     * @return int
     */
    public function getNumberOfReplicas(): int
    {
        return $this->indexConfig->getNumberOfReplicas();
    }

    /**
     * @return CharFilterCollection
     */
    public function getCharFilter(): CharFilterCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->charFilter;
    }

    /**
     * @return TokenizerCollection
     */
    public function getTokenizer(): TokenizerCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->tokenizer;
    }

    /**
     * @return TokenFilterCollection
     */
    public function getTokenFilter(): TokenFilterCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->tokenFilter;
    }

    /**
     * @return AnalyzerCollection
     */
    public function getAnalyzer(): AnalyzerCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->analyzer;
    }
}