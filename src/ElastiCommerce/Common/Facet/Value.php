<?php
namespace SmartDevs\ElastiCommerce\Common\Facet;

/**
 * Class Value
 * @package SmartDevs\ElastiCommerce\Common\Facet
 */
class Value
{
    /**
     * @var mixed
     */
    protected $_value;
    /**
     * @var string
     */
    protected $_label;
    /**
     * @var int
     */
    protected $_docCount = 0;

    /**
     * Value constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if(array_key_exists('key', $data)){
            $this->_value = $data['key'];
        }
        if(array_key_exists('doc_count', $data)){
            $this->_docCount = $data['doc_count'];
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param mixed $value
     * @return Value
     */
    public function setValue($value): Value
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param mixed $label
     * @return Value
     */
    public function setLabel($label): Value
    {
        $this->_label = $label;
        return $this;
    }

    /**
     * @return int
     */
    public function getDocCount(): int
    {
        return $this->_docCount;
    }

    /**
     * @param int $docCount
     * @return Value
     */
    public function setDocCount(int $docCount): Value
    {
        $this->_docCount = $docCount;

        return $this;
    }


}