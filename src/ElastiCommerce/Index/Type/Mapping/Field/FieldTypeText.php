<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field\FieldTypeImplementor;

final class FieldTypeText extends FieldTypeBase implements FieldTypeImplementor
{
    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [
        'analyzer',
        'search_analyzer',
        'boost',
        'fields',
        'copy_to'
    ];

    /**
     * valid boolean attributes
     *
     * @var string[]
     */
    protected $validAttributes = ['type', 'index', 'store', 'fielddata', 'include_in_all'];

    /**
     * valid types to represent this object
     *
     * @var string[]
     */
    protected $validTypes = ['text'];

    /**
     * fields collection
     *
     * @var TypeTextFieldCollection
     */
    protected $fields = null;

    public function setXmlConfig(\SimpleXMLElement $xml)
    {
        if (true === isset($xml->fields) && true === $this->hasValueChildren($xml->fields)) {
            $this->fields = new FieldTypeTextFieldCollection();
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
     * @return TypeStringFieldCollection
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
        if ($this->fields instanceof FieldTypeTextFieldCollection && $this->fields->count() > 0) {
            $return['fields'] = $this->fields->toSchema();
        }
        return $return;
    }
}