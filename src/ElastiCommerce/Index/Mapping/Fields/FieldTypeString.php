<?php

namespace SmartDevs\ElastiCommerce\Index\Mapping\Fields;

final class FieldTypeString extends FieldTypeBase implements FieldTypeInterface
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
        'copy_to'
    ];

    /**
     * valid types to represent this object
     *
     * @var string[]
     */
    protected $validTypes = ['string'];

    /**
     * fields collection
     *
     * @var TypeStringFieldCollection
     */
    protected $fields = null;

    public function setXmlConfig(\SimpleXMLElement $xml)
    {
        if (true === isset($xml->fields) && true === $this->hasValueChildren($xml->fields)) {
            $this->fields = new FieldTypeStringFieldCollection();
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
        if ($this->fields instanceof FieldTypeStringFieldCollection && $this->fields->count() > 0) {
            $return['fields'] = $this->fields->toSchema();
        }
        return $return;
    }
}