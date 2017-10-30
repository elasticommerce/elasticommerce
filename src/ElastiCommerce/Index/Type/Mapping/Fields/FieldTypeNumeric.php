<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Fields;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field\FieldTypeImplementor;

final class FieldTypeNumeric extends FieldTypeBase implements FieldTypeImplementor
{
    protected $validTypes = [
        'long',
        'integer',
        'short',
        'byte',
        'double',
        'float'
    ];

    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [
        'type',
        'index',
        'store'
    ];
}