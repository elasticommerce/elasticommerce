<?php

namespace SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field;

interface FieldTypeImplementor
{
    /**
     * builds a field type by xml config
     *
     * @param \SimpleXMLElement $xml
     * @return FieldTypeImplementor
     */
    public function setXmlConfig(\SimpleXMLElement $xml);

    /**
     * return array interpretation for mapping schema
     *
     * @return array
     */
    public function toSchema();

    /**
     * valid mapping types for this field type
     *
     * @param string $type
     * @return FieldTypeImplementor
     * @throws \InvalidArgumentException
     */
    public function setType($type);

    /**
     * set mapping name
     *
     * @param $name
     * @return FieldTypeBase
     */
    public function setName($name);

    /**
     * get mapping name
     *
     * @return string
     */
    public function getName();
}