<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;

use SmartDevs\ElastiCommerce\Util\Data\{
    DataCollection, DataObject
};

class FieldCollection extends DataCollection
{

    protected $type = null;

    protected $name = null;

    public function __construct()
    {
        $this->setItemObjectClass(DataObject::class);
    }

    /**
     * set type
     *
     * @param string $type
     * @return FieldCollection
     */
    public function setType($type)
    {
        if (false === in_array($type, $this->validTypes, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid type "%s" given', $type));
        }
        $this->type = $type;
        return $this;
    }

    /**
     * get type
     *
     * @param string $type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * set type
     *
     * @param string $type
     * @return FieldCollection
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * get name
     *
     * @param string $type
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setXmlConfig(\SimpleXMLElement $xml)
    {
        foreach ($xml->children() as $name => $data) {
            $property = $this->getTypeInstance(strval($data['type']));
            $property->setName($name);
            $property->setXmlConfig($data);
            //add item to collection
            $this->addItem($property);
        }
        return $this;
    }

    protected function getTypeInstance($type)
    {
        switch ($type) {
            case 'string': {
                $instance = new FieldTypeString();
                break;
            }
            case 'long':
            case 'integer':
            case 'short':
            case 'byte':
            case 'double':
            case 'float': {
                $instance = new FieldTypeNumeric();
                break;
            }
            case 'nested': {
                $instance = new FieldTypeNested();
                break;
            }
            case 'date': {
                $instance = new FieldTypeDate();
                break;
            }

            default: {
                throw new \InvalidArgumentException(sprintf('Invalid type class "%s" given', $type));
            }
        }
        return $instance->setType($type);
    }

    /**
     * convert attribute to schema
     *
     * @return array
     */
    public function toSchema()
    {
        $return = [];
        foreach ($this->getItems() as $name => $property) {
            $return[$name] = $property->toSchema();
        }
        return $return;
    }

    /**
     * @param string $name
     * @param string $type
     * @return FieldTypeBase
     */
    public function getField($name, $type = '')
    {
        //field does not exist create a new one
        $property = $this->getItemById($name);
        if ($property === null) {
            $property = $this->getTypeInstance($type);
            $property->setName($name);
            //add item to collection
            $this->addItem($property);
        }
        return $property;
    }
}