<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

use SmartDevs\ElastiCommerce\Util\Data\DataObject;

/**
 * Class AbstractTokenizer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer
 */
abstract class AbstractTokenizer extends DataObject
{
    public function __construct()
    {
        parent::__construct();
        $this->setIdFieldName('name');
        $this->setData('type', static::TYPE);
    }

    /**
     * add Tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenizer
     */
    abstract public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenizer;

    /**
     * get current tokenizer as array
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