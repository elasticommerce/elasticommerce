<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

use \SmartDevs\ElastiCommerce\Index\Analysis\CharFilter\{
    HTMLStripCharFilter,
    MappingCharFilter,
    PatternReplaceCharFilter
};

class CharFilterCollection extends AbstractCollection
{

    /**
     * node name in xml tree
     */
    const NODE_NAME = 'character_filter';

    /**
     * @var string[]
     */
    protected $classMapping = [
        HTMLStripCharFilter::TYPE => HTMLStripCharFilter::class,
        MappingCharFilter::TYPE => MappingCharFilter::class,
        PatternReplaceCharFilter::TYPE => PatternReplaceCharFilter::class
    ];
}