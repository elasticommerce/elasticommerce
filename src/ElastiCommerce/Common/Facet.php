<?php
namespace SmartDevs\ElastiCommerce\Common;

use SmartDevs\ElastiCommerce\Common\Facet\Value;
use SmartDevs\ElastiCommerce\Util\Data\DataCollection;
use SmartDevs\ElastiCommerce\Util\Data\DataObject;

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
     * @throws \Exception
     */
    public function setValues(array $values): Facet
    {
        $data = new DataCollection();
        if(is_array($values)){
            foreach ($values as $key => $value){
                $dataObject = new DataObject();
                if(is_array($value)) {
                    $dataObject->setData('value', $value['key']);
                    $dataObject->setData('doc_count', $value['doc_count']);
                }elseif($value instanceof Value){
                    $dataObject->setData('value', $value->getValue());
                    $dataObject->setData('doc_count', $value->getDocCount());
                }
                $data->addItem($dataObject);
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