<?php
namespace SmartDevs\ElastiCommerce\Util\Data;

use \BadMethodCallException,
    \ArrayAccess,
    \Serializable;

/**
 * Base Object
 *
 * @category   SmartDevs
 * @package    SmartDevs\ElastiCommerce\Util\Object
 * @author     Daniel Niedergesäß <dn@smart-devs.rocks>
 */
class DataObject implements ArrayAccess, Serializable
{

    /**
     * Object attributes
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Data changes flag (true after setData|unsetData call)
     * @var boolean
     */
    protected $_hasDataChanges = false;

    /**
     * Original data that was loaded
     *
     * @var array
     */
    protected $_origData;

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = null;

    /**
     * Setter/Getter underscore transformation cache
     *
     * @var array
     */
    protected static $_underscoreCache = array();

    /**
     * Object delete flag
     *
     * @var boolean
     */
    protected $_isDeleted = false;

    /**
     * Map short fields names to its full names
     *
     * @var array
     */
    protected $_oldFieldsMap = array();

    /**
     * Map of fields to sync to other fields upon changing their data
     *
     * @var array
     */
    protected $_syncFieldsMap = array();

    /**
     * Constructor
     *
     * By default is looking for first argument as array and assignes it as object attributes
     * This behaviour may change in child classes
     *
     */
    public function __construct()
    {
        $this->_initOldFieldsMap();
        if ($this->_oldFieldsMap) {
            $this->_prepareSyncFieldsMap();
        }

        $args = func_get_args();
        if (true === empty($args[0])) {
            $args[0] = array();
        }
        $this->_data = $args[0];
        $this->_addFullNames();

        $this->_construct();
    }

    protected function _addFullNames()
    {
        $existedShortKeys = array_intersect($this->_syncFieldsMap, array_keys($this->_data));
        if (!empty($existedShortKeys)) {
            foreach ($existedShortKeys as $key) {
                $fullFieldName = array_search($key, $this->_syncFieldsMap);
                $this->_data[$fullFieldName] = $this->_data[$key];
            }
        }
    }

    /**
     * Inits mapping array of object's previously used fields to new fields.
     * Must be overloaded by descendants to set concrete fields map.
     *
     * @return DataObject
     */
    protected function _initOldFieldsMap()
    {
        return $this;
    }

    /**
     * Called after old fields are inited. Forms synchronization map to sync old fields and new fields
     * between each other.
     *
     * @return DataObject
     */
    protected function _prepareSyncFieldsMap()
    {
        $old2New = $this->_oldFieldsMap;
        $new2Old = array_flip($this->_oldFieldsMap);
        $this->_syncFieldsMap = array_merge($old2New, $new2Old);
        return $this;
    }

    /**
     * Internal constructor not depended on params. Can be used for object initialization
     * @return DataObject
     */
    protected function _construct()
    {
        return $this;
    }

    /**
     * Set _isDeleted flag value (if $isDeleted param is defined) and return current flag value
     *
     * @param boolean $isDeleted
     * @return boolean
     */
    public function isDeleted($isDeleted = null)
    {
        $result = $this->_isDeleted;
        if (false === !is_null($isDeleted)) {
            $this->_isDeleted = $isDeleted;
        }
        return $result;
    }

    /**
     * Get data change status
     *
     * @return bool
     */
    public function hasDataChanges()
    {
        return $this->_hasDataChanges;
    }

    /**
     * set name of object id field
     *
     * @param   string $name
     * @return  DataObject
     */
    public function setIdFieldName($name)
    {
        $this->_idFieldName = $name;
        return $this;
    }

    /**
     * Retrieve name of object id field
     *
     * @return  string $name
     */
    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    /**
     * Retrieve object id
     * default field name "id"
     *
     * @return mixed
     */
    public function getId()
    {
        if ($this->getIdFieldName()) {
            return $this->_getData($this->getIdFieldName());
        }
        return $this->_getData('id');
    }

    /**
     * Set object id field value
     *
     * @param   mixed $value
     * @return  DataObject
     */
    public function setId($value)
    {
        if ($this->getIdFieldName()) {
            $this->setData($this->getIdFieldName(), $value);
        } else {
            $this->setData('id', $value);
        }
        return $this;
    }

    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @param  array $arr
     * @return DataObject
     */
    public function addData(array $arr)
    {
        foreach ($arr as $index => $value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param string|array $key
     * @param mixed $value
     * @return DataObject
     */
    public function setData($key, $value = null)
    {
        $this->_hasDataChanges = true;
        if (true === is_array($key)) {
            $this->_data = $key;
            $this->_addFullNames();
        } else {
            $this->_data[$key] = $value;
            if (isset($this->_syncFieldsMap[$key])) {
                $fullFieldName = $this->_syncFieldsMap[$key];
                $this->_data[$fullFieldName] = $value;
            }
        }
        return $this;
    }

    /**
     * Unset data from the object.
     *
     * $key can be a string only. Array will be ignored.
     *
     * @param string $key
     * @return DataObject
     */
    public function unsetData($key = null)
    {
        $this->_hasDataChanges = true;
        if ($key === null) {
            $this->_data = array();
        }elseif (true === is_array($key)){
            foreach ($key as $element){
                $this->unsetData($element);
            }
        } else {
            unset($this->_data[$key]);
            if (isset($this->_syncFieldsMap[$key])) {
                $fullFieldName = $this->_syncFieldsMap[$key];
                unset($this->_data[$fullFieldName]);
            }
        }
        return $this;
    }

    /**
     * Unset old fields data from the object.
     *
     * $key can be a string only. Array will be ignored.
     *
     * @param string $key
     * @return DataObject
     */
    public function unsetOldData($key = null)
    {
        if ($key === null) {
            foreach ($this->_oldFieldsMap as $key => $newFieldName) {
                unset($this->_data[$key]);
            }
        } else {
            unset($this->_data[$key]);
        }
        return $this;
    }

    /**
     * Retrieves data from the object
     *
     * accept a/b/c as ['a']['b']['c']
     *
     * If $key is empty will return all the data as an array
     * Otherwise it will return value of the attribute specified by $key
     *
     * If $index is specified it will assume that attribute data is an array
     * and retrieve corresponding member.
     *
     * @param string $key
     * @param string|int $index
     * @return mixed
     */
    public function getData($key = '', $index = null)
    {
        if ('' === $key || null === $key) {
            return $this->_data;
        }

        $default = null;

        if ((int)strpos($key, '/') > 1) {
            $keyArr = explode('/', $key);
            $data = $this->_data;
            foreach ($keyArr as $i => $k) {
                if ($k === '') {
                    return $default;
                }
                if (true === is_array($data)) {
                    if (false === isset($data[$k])) {
                        return $default;
                    }
                    $data = $data[$k];
                } elseif ($data instanceof DataObject) {
                    $data = $data->getData($k);
                } else {
                    return $default;
                }
            }
            return $data;
        }

        // legacy functionality for $index
        if (isset($this->_data[$key])) {
            if ($index === null) {
                return $this->_data[$key];
            }

            $value = $this->_data[$key];
            if (true === is_array($value)) {
                if (true === isset($value[$index])) {
                    return $value[$index];
                }
                return null;
            } elseif (true === is_string($value)) {
                $arr = explode("\n", $value);
                return (isset($arr[$index]) && (!empty($arr[$index]) || strlen($arr[$index]) > 0))
                    ? $arr[$index] : null;
            } elseif ($value instanceof DataObject) {
                return $value->getData($index);
            }
            return $default;
        }
        return $default;
    }

    /**
     * Get value from _data array without parse key
     *
     * @param   string $key
     * @return  mixed
     */
    protected function _getData($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    /**
     * Set object data with calling setter method
     *
     * @param string $key
     * @param mixed $args
     * @return DataObject
     */
    public function setDataUsingMethod($key, $args = array())
    {
        $method = 'set' . $this->_camelize($key);
        $this->{$method}($args);
        return $this;
    }

    /**
     * Get object data by key with calling getter method
     *
     * @param string $key
     * @param mixed $args
     * @return mixed
     */
    public function getDataUsingMethod($key, $args = null)
    {
        $method = 'get' . $this->_camelize($key);
        return $this->$method($args);
    }

    /**
     * Fast get data or set default if value is not available
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getDataSetDefault($key, $default)
    {
        if (false === isset($this->_data[$key])) {
            $this->_data[$key] = $default;
        }
        return $this->_data[$key];
    }

    /**
     * If $key is empty, checks whether there's any data in the object
     * Otherwise checks if the specified attribute is set.
     *
     * @param string $key
     * @return boolean
     */
    public function hasData($key = '')
    {
        if (empty($key) || !is_string($key)) {
            return !empty($this->_data);
        }
        return array_key_exists($key, $this->_data);
    }

    /**
     * Set required array elements
     *
     * @param   array $arr
     * @param   array $elements
     * @return  array
     */
    protected function _prepareArray(&$arr, array $elements = array())
    {
        foreach ($elements as $element) {
            if (false === isset($arr[$element])) {
                $arr[$element] = null;
            }
        }
        return $arr;
    }

    /**
     * Public wrapper for __toArray
     *
     * @param array $arrAttributes array of required attributes
     * @return array
     */
    public function toArray(array $arrAttributes = array())
    {
        if (true === empty($arrAttributes)) {
            return $this->_data;
        }

        $arrRes = array();
        foreach ($arrAttributes as $attribute) {
            if (true === isset($this->_data[$attribute])) {
                $arrRes[$attribute] = $this->_data[$attribute];
            } else {
                $arrRes[$attribute] = null;
            }
        }
        return $arrRes;
    }

    /**
     * Convert object attributes to XML
     *
     * @param array $arrAttributes array of required attributes
     * @param string $rootName name of the root element
     * @return string
     */
    public function toXml(array $arrAttributes = array(), $rootName = 'item', $addOpenTag = false, $addCdata = true)
    {
        $xml = '';
        if ($addOpenTag) {
            $xml .= '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        }
        if (!empty($rootName)) {
            $xml .= '<' . $rootName . '>' . "\n";
        }
        $xmlModel = new \SimpleXMLElement('<node></node>');
        $arrData = $this->toArray($arrAttributes);
        foreach ($arrData as $fieldName => $fieldValue) {
            if ($addCdata === true) {
                $fieldValue = "<![CDATA[$fieldValue]]>";
            } else {
                $fieldValue = htmlspecialchars($fieldValue, ENT_XML1 | ENT_COMPAT, 'UTF-8');
            }
            $xml .= "<$fieldName>$fieldValue</$fieldName>" . "\n";
        }
        if (!empty($rootName)) {
            $xml .= '</' . $rootName . '>' . "\n";
        }
        return $xml;
    }

    /**
     * Convert object attributes to JSON
     *
     * @param array $arrAttributes array of required attributes
     * @return string
     */
    public function toJson(array $arrAttributes = array())
    {
        return json_encode($this->toArray($arrAttributes));
    }

    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     * @throws  \BadMethodCallException
     */
    public function __call($method, $args)
    {
        if (true === isset($method[3]) && true === ctype_upper($method[3])) {
            switch (substr($method, 0, 3)) {
                case 'get' :
                    $key = $this->_underscore(substr($method, 3));
                    $data = $this->getData($key, isset($args[0]) ? $args[0] : null);
                    return $data;

                case 'set' :
                    $key = $this->_underscore(substr($method, 3));
                    $result = $this->setData($key, isset($args[0]) ? $args[0] : null);
                    return $result;

                case 'uns' :
                    $key = $this->_underscore(substr($method, 3));
                    $result = $this->unsetData($key);
                    return $result;

                case 'has' :
                    $key = $this->_underscore(substr($method, 3));
                    return isset($this->_data[$key]);
            }
        }
        throw new BadMethodCallException("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, true) . ")");
    }

    /**
     * Attribute getter (deprecated)
     *
     * @param string $var
     * @return mixed
     */

    public function __get($var)
    {
        throw new BadMethodCallException('Invalid method ' . get_class($this) . '::__get(' . $var . ')');
    }

    /**
     * Attribute setter (deprecated)
     *
     * @param string $var
     * @param mixed $value
     */
    public function __set($var, $value)
    {
        throw new BadMethodCallException('Invalid method ' . get_class($this) . '::__set(' . $var . ', ' . print_r($value, true) . ')');
    }

    /**
     * checks whether the object is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if (true === empty($this->_data)) {
            return true;
        }
        return false;
    }

    /**
     * Converts field names for setters and geters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unneccessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = preg_replace('/_+/', '_', strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name)));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }

    protected function _camelize($name)
    {
        return str_replace(' ', '_', ucwords(str_replace('_', ' ', $name)));
    }

    /**
     * serialize this object
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            'id_field' => $this->_idFieldName,
            'data' => $this->_data
        ]);
    }

    /**
     * rebuilds object from string
     *
     * @param string $data
     * @return DataObject
     */
    public function unserialize($data)
    {
        $content = unserialize($data);
        if (true === isset($content['id_field']) && strlen($content['id_field']) > 0) {
            $this->setIdFieldName($content['id_field']);
        }
        $this->setData($content['data']);
        $this->_hasDataChanges = false;
        return $this;
    }

    /**
     * Get object loaded data (original data)
     *
     * @param string $key
     * @return mixed
     */
    public function getOrigData($key = null)
    {
        if ($key === null) {
            return $this->_origData;
        }
        return true === isset($this->_origData[$key]) ? $this->_origData[$key] : null;
    }

    /**
     * Initialize object original data
     *
     * @param string $key
     * @param mixed $data
     * @return DataObject
     */
    public function setOrigData($key = null, $data = null)
    {
        if ($key === null) {
            $this->_origData = $this->_data;
        } else {
            $this->_origData[$key] = $data;
        }
        return $this;
    }

    /**
     * Compare object data with original data
     *
     * @param string $field
     * @return boolean
     */
    public function dataHasChangedFor($field)
    {
        $newData = $this->getData($field);
        $origData = $this->getOrigData($field);
        return $newData != $origData;
    }

    /**
     * Clears data changes status
     *
     * @param boolean $value
     * @return DataObject
     */
    public function setDataChanges($value)
    {
        $this->_hasDataChanges = (bool)$value;
        return $this;
    }

    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetset.php
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_data[$offset] = $value;
    }

    /**
     * Implementation of ArrayAccess::offsetExists()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetUnset()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetunset.php
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_data[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetget.php
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_data[$offset]) ? $this->_data[$offset] : null;
    }


    /**
     * check field is dirty
     *
     * @param string $field
     * @return boolean
     */
    public function isDirty($field = null)
    {
        if (empty($this->_dirty)) {
            return false;
        }
        if ($field === null) {
            return true;
        }
        return isset($this->_dirty[$field]);
    }

    /**
     * Flag field as dirty
     *
     * @param string $field
     * @param boolean $flag
     * @return DataObject
     */
    public function flagDirty($field, $flag = true)
    {
        if ($field === null) {
            foreach ($this->getData() as $field => $value) {
                $this->flagDirty($field, $flag);
            }
        } else {
            if ($flag) {
                $this->_dirty[$field] = true;
            } else {
                unset($this->_dirty[$field]);
            }
        }
        return $this;
    }
}
