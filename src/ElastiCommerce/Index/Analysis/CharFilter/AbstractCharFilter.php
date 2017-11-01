<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;
use SmartDevs\ElastiCommerce\Util\Data\{DataObject,DataCollection};

abstract class AbstractCharFilter extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add char filter type data
     */
    abstract public function setXmlConfig(\SimpleXMLElement $element);

    /**
     * get current object as array
     */
    public function toSchema()
    {
        $data = $this->getData();
        unset($data['name']);
        return $data;
    }
}