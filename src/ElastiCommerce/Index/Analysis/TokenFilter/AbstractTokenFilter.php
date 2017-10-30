<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;
use SmartDevs\ElastiCommerce\Util\Data\{DataObject,DataCollection};


abstract class AbstractTokenFilter extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add Token Filter type data
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter\AbstractTokenFilter
     */
    abstract public function setXmlConfig(\SimpleXMLElement $element);

    /**
     * get current object as array
     */
    public function asConfig()
    {
        $data = $this->getData();
        unset($data['name']);
        return $data;
    }
}