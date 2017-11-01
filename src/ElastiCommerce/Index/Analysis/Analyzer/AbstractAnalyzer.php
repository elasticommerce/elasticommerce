<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

use SmartDevs\ElastiCommerce\Util\Data\DataObject;

/**
 * Class AbstractAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
abstract class AbstractAnalyzer extends DataObject
{
    /**
     * valid array keys mapped as properties
     *
     * @var array
     */
    protected $validProperties = [];

    /**
     * AbstractAnalyzer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * checks a property is valid
     *
     * @return bool
     */
    protected function isPropertyValid($property): bool
    {
        return in_array($property, $this->validProperties);
    }

    /**
     * init object from array config
     *
     * @param array $config
     * @return AbstractAnalyzer
     */
    public function setConfig(array $config): AbstractAnalyzer
    {
        foreach ($config as $key => $data) {
            if (true === $this->isPropertyValid($key)) {
                $this->setDataUsingMethod($key, $data);
            }
        }
        return $this;
    }

    /**
     * init object from xml config
     *
     * @param \SimpleXMLElement $xml
     * @return AbstractAnalyzer
     */
    public function setXmlConfig(\SimpleXMLElement $xml): AbstractAnalyzer
    {
        foreach ($this->validProperties as $property) {
            //skip check we have an non existent or empty property
            if (false === isset($xml->{$property}) || true === empty($xml->{$property})) {
                continue;
            }
            if (count($xml->{$property}->children()) > 0) {
                $this->setDataUsingMethod($property,
                    array_values(
                        array_map(function ($item) {
                            return false === empty(strval($item)) ? strval($item) : strval($item->getName());
                        }, (array)$xml->{$property}->children())
                    )
                );
            } else {
                $this->setDataUsingMethod($property, strval($xml->{$property}));
            }
        }
        return $this;
    }

    /**
     * get current object as array
     *
     * @return array
     */
    public function toSchema(): array
    {
        $data = $this->getData();
        unset($data['name']);
        return $data;
    }
}