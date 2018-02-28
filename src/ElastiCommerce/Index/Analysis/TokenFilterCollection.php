<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

use SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;


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
        'decompound' => TokenFilter\DecompoundTokenFilter::class,
        'stop' => TokenFilter\StopTokenFilter::class,
        'snowball' => TokenFilter\SnowballTokenFilter::class,
        TokenFilter\EdgeNgramTokenFilter::TYPE => TokenFilter\EdgeNgramTokenFilter::class,
        TokenFilter\ShingleTokenFilter::TYPE => TokenFilter\ShingleTokenFilter::class,
        TokenFilter\SynonymTokenFilter::TYPE => TokenFilter\SynonymTokenFilter::class
    ];
}