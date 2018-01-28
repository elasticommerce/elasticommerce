<?php
namespace SmartDevs\ElastiCommerce\Common\Facet;

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
     * Adding item to item array
     *
     * @param Facet $facet
     * @return Collection
     */
    public function addFacet(Facet $facet)
    {
        $this->_facets[$facet->getCode()] = $facet;
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

}