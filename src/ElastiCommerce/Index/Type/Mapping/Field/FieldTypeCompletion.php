<?php

namespace SmartDevs\ElastiCommerce\Index\Type\Mapping\Field;

use SmartDevs\ElastiCommerce\Implementor\Index\Type\Mapping\Field\FieldTypeImplementor;

final class FieldTypeCompletion extends FieldTypeBase implements FieldTypeImplementor
{
    /**
     * valid parameters for generating mapping schema
     *
     * @var string[]
     */
    protected $supportedParameters = [
        'analyzer',
        'search_analyzer',
        'preserve_separators',
        'preserve_position_increments',
        'max_input_length'
    ];

    /**
     * valid types to represent this object
     *
     * @var string[]
     */
    protected $validTypes = ['completion'];

    /**
     * valid boolean attributes
     *
     * @var string[]
     */
    protected $validAttributes = ['type'];
}