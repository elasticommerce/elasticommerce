<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\MappingImplementor;
use SmartDevs\ElastiCommerce\Implementor\Index\TypeImplementor;
use SmartDevs\ElastiCommerce\Util\Data\DataObject;

class Type extends DataObject implements TypeImplementor
{

    /**
     * @var MappingImplementor
     */
    protected $mapping = null;

    public function __construct()
    {
        $this->setIdFieldName(TypeImplementor::NAME_FIELD_KEY);
        return parent::__construct();
    }

    /**
     * set index type name
     *
     * @param string $name
     * @return TypeImplementor
     */
    public function setName(string $name): TypeImplementor
    {
        if (true === empty($name)) {
            throw new \InvalidArgumentException('missing required name');
        }
        $this->setId($name);
        return $this;
    }

    /**
     * get index type name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getId('name');
    }

    /**
     * set index type mapping
     *
     * @param MappingImplementor $mapping
     * @return TypeImplementor
     */
    public function setMapping(MappingImplementor $mapping): TypeImplementor
    {
        $this->mapping = $mapping;
        return $this;
    }

    /**
     * get index type mapping
     *
     * @return MappingImplementor
     */
    public function getMapping(): MappingImplementor
    {
        return $this->mapping;
    }

    public function getMappingFields()
    {
        return $this->mapping->getFields();
    }

    /**
     * checks type has valid mapping
     *
     * @return bool
     */
    public function hasMapping(): bool
    {
        return null !== $this->mapping && $this->mapping instanceof MappingImplementor;
    }
}