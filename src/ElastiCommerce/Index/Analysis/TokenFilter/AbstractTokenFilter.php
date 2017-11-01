<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

use SmartDevs\ElastiCommerce\Util\Data\DataObject;

/**
 * Class AbstractTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
abstract class AbstractTokenFilter extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add Token Filter type data
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenFilter
     */
    abstract public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter;

    /**
     * get current token filter as array
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