<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;


final class FieldTypeObject extends FieldTypeBase
{
    /**
     * valid types to represent this object
     *
     * @var array
     */
    protected $validTypes = ['object'];

    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [];

    /**
     * valid boolean attributes
     *
     * @var string[]
     */
    protected $validAttributes = ['type', 'include_in_all'];

    public function setXmlConfig(\SimpleXMLElement $xml)
    {
        $this->setData('collection', new FieldCollection());
        $this->getCollection()->setName($xml->getName());
        $this->getCollection()->setXmlConfig($xml);
        return $this;
    }

    public function toSchema()
    {
        $return = parent::toSchema();
        $return['properties'] = $this->getCollection()->toSchema();
        return $return;
    }
}