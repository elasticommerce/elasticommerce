<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Exception;
use SmartDevs\ElastiCommerce\Implementor\Config;
use SmartDevs\ElastiCommerce\Index\Mapping\DynamicTemplatesCollection;
use SmartDevs\ElastiCommerce\Index\Mapping\Fields\FieldCollection;

class Mappings
{

    /**
     * @var bool
     */
    protected $isInitialized = null;

    /**
     * @var Config
     */
    protected $config = null;

    /**
     * list of dynamic templates
     *
     * @var DynamicTemplatesCollection
     */
    protected $dynamicTemplates = null;

    /**
     * mapping fields
     *
     * @var FieldCollection
     */
    protected $mapping = null;

    /**
     * Mappings constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->isInitialized = false;
    }

    /**
     * set flag the class is initialized (lazy loading)
     *
     * @param bool $value
     * @return Mappings
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
     * @return Mappings
     */
    protected function initialize(): Mappings
    {
        $this->readMappingFromConfigFile();
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
     * init mapping from config file
     *
     * @return $this
     * @throws \Exception
     */
    protected function readMappingFromConfigFile(): Mappings
    {
        $configFile = $this->config->getIndexConfig()->getSchemaConfigFile();
        if (true === $this->isConfigFileReadable($configFile)) {
            $xml = simplexml_load_file($configFile);
        }
        if (false === isset($xml) || false === $xml instanceof \SimpleXMLElement) {
            throw new Exception('missing valid schema config file');
        }
        $this->initMappingsFromXml($xml);
        return $this;
    }

    /**
     * init dynamic templates and mapping
     *
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    protected function initMappingsFromXml(\SimpleXMLElement $xml): Mappings
    {
        //init dynamic templates
        #$this->dynamicTemplates = new DynamicTemplatesCollection();
        #if (true === property_exists($xml, 'dynamic_templates')) {
        #    $this->dynamicTemplates->setXmlConfig($xml->dynamic_templates->children());
        #}
        //init mapping fields
        $this->mapping = new FieldCollection();
        if (true === property_exists($xml, 'mapping')) {
            $this->mapping->setName('properties');
            $this->mapping->setXmlConfig($xml->mapping);
        }
        return $this;
    }

    /**
     * get collection of dynamic templates
     *
     * @return DynamicTemplatesCollection
     */
    public function getDynamicTemplates(): DynamicTemplatesCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->dynamicTemplates;
    }

    /**
     * get index mapping
     *
     * @return FieldCollection
     */
    public function getMapping(): FieldCollection
    {
        if (false === $this->isInitialized()) {
            $this->initialize();
        }
        return $this->mapping;
    }
}