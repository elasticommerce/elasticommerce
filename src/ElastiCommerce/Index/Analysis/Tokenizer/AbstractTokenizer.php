<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

abstract class AbstractTokenizer extends \SmartDevs\Util\Data\DataObject
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