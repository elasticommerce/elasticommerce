<?php

declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping;

use SmartDevs\ElastiCommerce\Util\Data\{
    DataCollection
};

/**
 * Class DynamicTemplatesCollection
 *
 */
class DynamicTemplateCollection extends DataCollection
{
    /**
     * DynamicTemplatesCollection constructor.
     */
    public function __construct()
    {
        $this->setItemObjectClass(DynamicTemplate::class);
    }

    /**
     * set xml config for dynamic templates
     *
     * @param \SimpleXMLElement $xml
     * @return $this
     */
    public function setXmlConfig(\SimpleXMLElement $xml): DynamicTemplateCollection
    {
        foreach ($xml as $name => $mapping) {
            $item = $this->getDynamicTemplate($name);
            $item->setXmlConfig($mapping);
        }
        return $this;
    }

    /**
     * get / create an dynamic template by name
     *
     * @param string $name
     * @param string $type
     * @return DynamicTemplate
     */
    public function getDynamicTemplate($name)
    {
        //field does not exist create a new one
        $property = $this->getItemById($name);
        if ($property === null) {
            $property = new DynamicTemplate();
            $property->setName($name);
            //add item to collection
            $this->addItem($property);
        }
        return $property;
    }

    public function toSchema(): array
    {
        $return = array();
        foreach ($this->getItems() as $dynamicTemplate) {
            /** @var $dynamicTemplate DynamicTemplate */
            $return[][$dynamicTemplate->getName()] = $dynamicTemplate->toSchema();
        }
        return $return;
    }
}