<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field\FieldTypeImplementor;

final class FieldTypeKeyword extends FieldTypeBase implements FieldTypeImplementor
{
    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [
        'type',
        'analyzer',
        'index_analyzer',
        'search_analyzer',
        'boost',
        'fields',
        'index',
        'store',
        'copy_to',
        'fielddata'
    ];

    /**
     * valid types to represent this object
     *
     * @var string[]
     */
    protected $validTypes = ['keyword'];

    /**
     * fields collection
     *
     * @var TypeKeywordFieldCollection
     */
    protected $fields = null;

    public function setXmlConfig(\SimpleXMLElement $xml)
    {
        if (true === isset($xml->fields) && true === $this->hasValueChildren($xml->fields)) {
            $this->fields = new FieldTypeKeywordFieldCollection();
            $this->fields->setXmlConfig($xml->fields);
            unset($xml->fields);
            //add fields
        }
        //call parent
        return parent::setXmlConfig($xml);
    }

    /**
     * get field by name
     *
     * @param $name
     */
    public function getField($name)
    {
        $this->fields->getItemById($name);
    }

    /**
     * get fields collection
     *
     * @return TypeKeywordFieldCollection
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * generate array for mapping schema
     *
     * @return array
     */
    public function toSchema()
    {
        $return = parent::toSchema();
        if ($this->fields instanceof FieldTypeKeywordFieldCollection && $this->fields->count() > 0) {
            $return['fields'] = $this->fields->toSchema();
        }
        return $return;
    }
}