<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field\FieldTypeImplementor;

final class FieldTypeBoolean extends FieldTypeBase implements FieldTypeImplementor
{
    protected $validTypes = [
        'boolean',
    ];

    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [
    ];

    /**
     * valid boolean attributes
     *
     * @var string[]
     */
    protected $validAttributes = ['type', 'index', 'store'];
}