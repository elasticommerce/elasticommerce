<?php

namespace SmartDevs\ElastiCommerce\Index\Mapping\Fields;

final class FieldTypeNumeric extends FieldTypeBase implements FieldTypeInterface
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