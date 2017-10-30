<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;
use SmartDevs\ElastiCommerce\Util\Data\{DataObject,DataCollection};
abstract class AbstractTokenizer extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add tokenizer type data
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