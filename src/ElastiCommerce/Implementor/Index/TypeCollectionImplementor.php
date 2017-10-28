<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Implementor\Index;

/**
 * Interface for index type
 */
interface TypeCollectionImplementor
{

    /**
     * add an type to an collection
     *
     * @param TypeImplementor $type
     * @return mixed
     */
    public function addType(TypeImplementor $type);

    /**
     * get an type by identifier
     *
     * @param string $type
     * @return TypeImplementor
     */
    public function getType(string $type): TypeImplementor;

    /**
     * get an new item class
     *
     * @return TypeImplementor
     */
    public function getNewType(): TypeImplementor;
}