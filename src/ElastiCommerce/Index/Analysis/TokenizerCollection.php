<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

use \SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    ClassicTokenizer,
    KeywordTokenizer,
    LowercaseTokenizer,
    StandardTokenizer,
    WhitespaceTokenizer,
    PathHierarchyTokenizer
};


class TokenizerCollection extends AbstractCollection
{

    /**
     * node name in xml tree
     */
    const NODE_NAME = 'tokenizer';

    /**
     * @var string[]
     */
    protected $classMapping = [
        ClassicTokenizer::TYPE => ClassicTokenizer::class,
        KeywordTokenizer::TYPE => KeywordTokenizer::class,
        LowercaseTokenizer::TYPE => LowercaseTokenizer::class,
        StandardTokenizer::TYPE => StandardTokenizer::class,
        WhitespaceTokenizer::TYPE => WhitespaceTokenizer::class,
        PathHierarchyTokenizer::TYPE => PathHierarchyTokenizer::class
    ];
}