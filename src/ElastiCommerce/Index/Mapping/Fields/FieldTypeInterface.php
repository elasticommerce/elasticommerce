<?php

namespace SmartDevs\ElastiCommerce\Index\Mapping\Fields;

interface FieldTypeInterface
{
    /**
     * builds a field type by xml config
     *
     * @param \SimpleXMLElement $xml
     * @return FieldTypeInterface
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
     * @return FieldTypeInterface
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
     * @return string|integer
     */
    public function getName();
}