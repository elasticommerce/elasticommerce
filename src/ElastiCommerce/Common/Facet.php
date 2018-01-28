<?php
namespace SmartDevs\ElastiCommerce\Common;

use SmartDevs\ElastiCommerce\Common\Facet\Value;

/**
 * Class Facet
 * @package SmartDevs\ElastiCommerce\Common
 */
class Facet
{
    protected $_code = "";
    protected $_label = "";
    protected $_docCount = 0;
    protected $_values = [];
    protected $_type;

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->_label;
    }

    /**
     * @param string $label
     *
     * @return Facet
     */
    public function setLabel(string $label): Facet
    {
        $this->_label = $label;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * @param $value
     * @param null $key
     */
    public function setValue($value, $key = null)
    {
        $this->_values[$key] = $value;
    }
    /**
     * @param array $values
     *
     * @return Facet
     */
    public function setValues(array $values): Facet
    {
        $data = new Collection();
        if(is_array($values)){
            foreach ($values as $key => $value){
                if(is_array($value)) {
                    $data->addItem(new Value($value), $key);
                }elseif($value instanceof Value){
                    $data->addItem($value, $key);
                }
            }
        }else{
            $data = $values;
        }
        $this->_values = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param string $code
     * @return Facet
     */
    public function setCode(string $code) : Facet
    {
        $this->_code = $code;

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
     *
     * @return Facet
     */
    public function setDocCount(int $docCount): Facet
    {
        $this->_docCount = $docCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     * @return Facet
     */
    public function setType($type): Facet
    {
        $this->_type = $type;

        return $this;
    }
}