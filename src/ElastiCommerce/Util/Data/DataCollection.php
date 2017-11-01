<?php
namespace SmartDevs\ElastiCommerce\Util\Data;

use \IteratorAggregate,
    \Countable,
    \Serializable,
    \ArrayIterator,
    LogicException,
    Exception;

/**
 * Base DataCollection
 *
 * @category   SmartDevs
 * @package    SmartDevs\ElastiCommerce\Util\Object
 * @author     Daniel Niedergesäß <dn@smart-devs.rocks>
 *
 */
class DataCollection implements IteratorAggregate, Countable, Serializable
{
    /**
     * Collection items
     *
     * @var DataObject[]
     */
    protected $_items = array();

    /**
     * Item object class name
     *
     * @var string
     */
    protected $_itemObjectClass = 'DataObject';

    /**
     * Total items number
     *
     * @var int
     */
    protected $_totalRecords = null;

    /**
     * Additional collection flags
     *
     * @var array
     */
    protected $_flags = array();

    /**
     * Retrieve collection all items count
     *
     * @return int
     */
    public function getSize()
    {
        if (null === $this->_totalRecords) {
            $this->_totalRecords = count($this->getItems());
        }
        return (int)$this->_totalRecords;
    }

    /**
     * Retrieve collection first item
     *
     * @return DataObject
     */
    public function getFirstItem()
    {
        if (count($this->_items) > 0) {
            reset($this->_items);
            return current($this->_items);
        }

        return new $this->{_itemObjectClass}();
    }

    /**
     * Retrieve collection last item
     *
     * @return DataObject
     */
    public function getLastItem()
    {
        if (count($this->_items) > 0) {
            return end($this->_items);
        }

        return new $this->{_itemObjectClass}();
    }

    /**
     * Retrieve collection items
     *
     * @return DataObject[]
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Retrieve field values from all items
     *
     * @param   string $colName
     * @return  array
     */
    public function getColumnValues($colName)
    {
        $col = array();
        foreach ($this->getItems() as $item) {
            $col[] = $item->getData($colName);
        }
        return $col;
    }

    /**
     * Search all items by field value
     *
     * @param   string $column
     * @param   mixed $value
     * @return  array
     */
    public function getItemsByColumnValue($column, $value)
    {
        $res = array();
        foreach ($this as $item) {
            if ($item->getData($column) == $value) {
                $res[] = $item;
            }
        }
        return $res;
    }

    /**
     * Search first item by field value
     *
     * @param   string $column
     * @param   mixed $value
     * @return  DataObject || null
     */
    public function getItemByColumnValue($column, $value)
    {
        foreach ($this as $item) {
            if ($item->getData($column) == $value) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Adding item to item array if its not already exists
     *
     * @param   DataObject $item
     * @return  DataCollection
     * @throws  Exception
     */
    public function addItem(DataObject $item)
    {
        $itemId = $this->_getItemId($item);

        if (false === is_null($itemId)) {
            if (true === isset($this->_items[$itemId])) {
                throw new Exception('Item (' . get_class($item) . ') with the same id "' . $item->getId() . '" already exist');
            }
            $this->_items[$itemId] = $item;
        } else {
            $this->_addItem($item);
        }
        return $this;
    }

    /**
     * Adding item to item array if overwrites if it exists
     *
     * @param   DataObject $item
     * @return  DataCollection
     * @throws  Exception
     */
    public function setItem(DataObject $item)
    {
        $itemId = $this->_getItemId($item);

        if (false === is_null($itemId)) {
            $this->_items[$itemId] = $item;
        } else {
            $this->_addItem($item);
        }
        return $this;
    }

    /**
     * Add item that has no id to collection
     *
     * @param DataObject $item
     * @return DataCollection
     */
    protected function _addItem(DataObject $item)
    {
        $this->_items[] = $item;
        return $this;
    }

    /**
     * Retrieve item id
     *
     * @param DataObject $item
     * @return mixed
     */
    protected function _getItemId(DataObject $item)
    {
        return $item->getId();
    }

    /**
     * Retrieve ids of all items
     *
     * @return array
     */
    public function getAllIds()
    {
        $ids = array();
        foreach ($this->getItems() as $item) {
            $ids[] = $this->_getItemId($item);
        }
        return $ids;
    }

    /**
     * Remove item from collection by item key
     *
     * @param   mixed $key
     * @return  DataCollection
     */
    public function removeItemByKey($key)
    {
        if (true === isset($this->_items[$key])) {
            unset($this->_items[$key]);
        }
        return $this;
    }

    /**
     * Clear collection
     *
     * @return DataCollection
     */
    public function clear()
    {
        $this->_items = array();
        $this->_totalRecords = null;
        return $this;
    }

    /**
     * @todo refactor
     *
     * Walk through the collection and run model method or external callback
     * with optional arguments
     *
     * Returns array with results of callback for each item
     *
     * @param string $method
     * @param array $args
     * @return array
     */
    public function walk($callback, array $args = array())
    {
        $results = array();
        $useItemCallback = true === is_string($callback) && strpos($callback, '::') === false;
        foreach ($this->getItems() as $id => $item) {
            if ($useItemCallback) {
                $cb = array($item, $callback);
            } else {
                $cb = $callback;
                array_unshift($args, $item);
            }
            $results[$id] = call_user_func_array($cb, $args);
        }
        return $results;
    }

    /**
     * @param $obj_method
     * @param array $args
     */
    public function each($objectMethod, $args = array())
    {
        foreach ($args->_items as $k => $item) {
            $args->_items[$k] = call_user_func($objectMethod, $item);
        }
    }

    /**
     * Setting data for all collection items
     *
     * @param   mixed $key
     * @param   mixed $value
     * @return  DataCollection
     */
    public function setDataToAll($key, $value = null)
    {
        if (true === is_array($key)) {
            foreach ($key as $k => $v) {
                $this->setDataToAll($k, $v);
            }
            return $this;
        }
        foreach ($this->getItems() as $item) {
            $item->setData($key, $value);
        }
        return $this;
    }

    /**
     * @todo implement interface
     * Set collection item class name
     *
     * need full path with namespace
     *
     * @param   string $className
     * @return  DataCollection
     * @throws  LogicException
     */
    public function setItemObjectClass($className)
    {
        if (false === is_a($className, DataObject::class, true)) {
            throw new LogicException($className . ' does not extends from DataObject');
        }
        $this->_itemObjectClass = $className;
        return $this;
    }

    /**
     * Retrieve collection empty item
     *
     * @return DataObject
     */
    public function getNewEmptyItem()
    {
        $className = $this->_itemObjectClass;
        return new $className();
    }

    /**
     * Convert collection to XML
     *
     * @return string
     */
    public function toXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <collection>
           <totalRecords>' . $this->getSize() . '</totalRecords>
           <items>';

        foreach ($this as $item) {
            $xml .= $item->toXml();
        }
        $xml .= '</items>
        </collection>';
        return $xml;
    }

    /**
     * Convert collection to array
     * @param  array $arrRequiredFields array of required attributes
     * @return array
     */
    public function toArray($arrRequiredFields = array())
    {
        $arrItems = array();
        $arrItems['totalRecords'] = $this->getSize();

        $arrItems['items'] = array();
        foreach ($this as $item) {
            $arrItems['items'][] = $item->toArray($arrRequiredFields);
        }
        return $arrItems;
    }

    /**
     * Convert collection to array
     *
     * @param  array $arrRequiredFields array of required attributes
     * @return string
     */
    public function toJson($arrRequiredFields = array())
    {
        return json_encode($this->toArray($arrRequiredFields));
    }

    /**
     * Convert items array to array for select options
     *
     * return items array
     * array(
     *      $index => array(
     *          'value' => mixed
     *          'label' => mixed
     *      )
     * )
     *
     * @param   string $valueField
     * @param   string $labelField
     * @param   array $additional
     * @return  array
     */
    protected function toOptionArray($valueField = 'id', $labelField = 'name', $additional = array())
    {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            foreach ($additional as $code => $field) {
                $data[$code] = $item->getData($field);
            }
            $res[] = $data;
        }
        return $res;
    }

    /**
     * Convert items array to hash for select options
     *
     * return items hash
     * array($value => $label)
     *
     * @param   string $valueField
     * @param   string $labelField
     * @return  array
     */
    protected function toOptionHash($valueField = 'id', $labelField = 'name')
    {
        $res = array();
        foreach ($this as $item) {
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
    }

    /**
     * serialize this object
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'flags' => $this->_flags,
            'itemObjectClass' => $this->_itemObjectClass,
            'items' => $this->_items
        ]);
    }

    /**
     * rebuilds object from string
     *
     * @param string $data
     * @return DataCollection
     */
    public function unserialize($data)
    {
        $content = unserialize($data);
        $this->_itemObjectClass['itemObjectClass'];
        $this->_flags = $content['flags'];
        $this->_items = $content['items'];
        return $this;
    }

    /**
     * Retrieve item by id
     *
     * @param   mixed $idValue
     * @return  DataObject
     */
    public function getItemById($idValue)
    {
        if (true === isset($this->_items[$idValue])) {
            return $this->_items[$idValue];
        }
        return null;
    }

    /**
     * Implementation of IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_items);
    }

    /**
     * Retireve count of collection loaded items
     *
     * @return int
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * Retrieve Flag
     *
     * @param string $flag
     * @return mixed
     */
    public function getFlag($flag)
    {
        return isset($this->_flags[$flag]) ? $this->_flags[$flag] : null;
    }

    /**
     * Set Flag
     *
     * @param string $flag
     * @param mixed $value
     * @return DataCollection
     */
    public function setFlag($flag, $value = null)
    {
        $this->_flags[$flag] = $value;
        return $this;
    }

    /**
     * Has Flag
     *
     * @param string $flag
     * @return bool
     */
    public function hasFlag($flag)
    {
        return array_key_exists($flag, $this->_flags);
    }
}
