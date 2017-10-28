<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

use \SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;


class TokenFilterCollection extends AbstractCollection
{

    /**
     * node name in xml tree
     */
    const NODE_NAME = 'token_filter';

    /**
     * @var string[]
     */
    protected $classMapping = [
    ];
}