<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Implementor\Index;

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


    public function getName(): string;
}