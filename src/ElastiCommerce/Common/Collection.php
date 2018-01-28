<?php
namespace SmartDevs\ElastiCommerce\Common;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use SmartDevs\ElastiCommerce\Common\Facet;

/**
 * Class Collection
 */
class Collection implements IteratorAggregate, Countable
{
    const SORT_ORDER_ASC    = 'ASC';
    const SORT_ORDER_DESC   = 'DESC';

    /**
     * Collection items
     *
     * @var array
     */
    protected $_facets = array();

    /**
     * Item object class name
     *
     * @var string
     */
    protected $_itemObjectClass = 'Facet';

    /**
     * @return array
     */
    public function sortByValue()
    {
        uasort($this->_facets, [$this, '_sortByValue']);
        return $this->_facets;
    }

    /**
     * @param $a \SmartDevs\ElastiCommerce\Common\Facet\Value
     * @param $b \SmartDevs\ElastiCommerce\Common\Facet\Value
     * @return bool
     */
    public function _sortByValue($a, $b)
    {
        return $a->getValue() > $b->getValue();
    }

    /**
     * Adding item to item array
     *
     * @param $item
     * @param null $key
     * @return Collection
     */
    public function addItem($item, $key = null)
    {
        $this->_facets[$key] = $item;
        return $this;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_facets);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_facets);
    }

    /**
     * Retrieve collection first item
     *
     * @return mixed | Facet\Value
     */
    public function getFirstItem()
    {
        if (count($this->_facets)) {
            reset($this->_facets);
            return current($this->_facets);
        }
    }

    /**
     * Retrieve collection last item
     *
     * @return mixed | Facet\Value
     */
    public function getLastItem()
    {
        if (count($this->_facets)) {
            return end($this->_facets);
        }
    }
}