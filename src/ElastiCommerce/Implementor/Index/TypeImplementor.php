<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Implementor\Index;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\MappingImplementor;

/**
 * Interface for index type
 */
interface TypeImplementor
{
    const NAME_FIELD_KEY = 'name';

    /**
     * set index type name
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return TypeImplementor
     */
    public function setName(string $name);

    /**
     * get index type name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * set index type mapping
     *
     * @param MappingImplementor $mapping
     * @return TypeImplementor
     */
    public function setMapping(MappingImplementor $mapping): TypeImplementor;

    /**
     * get index type mapping
     *
     * @return MappingImplementor
     */
    public function getMapping(): MappingImplementor;

    /**
     * checks type has valid mapping
     *
     * @return bool
     */
    public function hasMapping(): bool;
}