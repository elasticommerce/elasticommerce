<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

use SmartDevs\ElastiCommerce\Util\Data\DataObject;

/**
 * Class AbstractCharFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\CharFilter
 */
abstract class AbstractCharFilter extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add char filter type data
     *
     * @param \SimpleXMLElement $element
     * @return AbstractCharFilter
     */
    abstract public function setXmlConfig(\SimpleXMLElement $element): AbstractCharFilter;

    /**
     * get current object as array
     *
     * @return array
     */
    public function toSchema(): array
    {
        $data = $this->getData();
        unset($data['name']);
        return $data;
    }
}