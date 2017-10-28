<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Implementor\Index\TypeImplementor;
use SmartDevs\ElastiCommerce\Util\Data\DataObject;

class Type extends DataObject implements TypeImplementor
{

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
}