<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

use \SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    CustomAnalyzer,
    KeywordAnalyzer,
    PatternAnalyzer,
    SimpleAnalyzer,
    StandardAnalyzer,
    StopAnalyzer,
    WhitespaceAnalyzer
};


class AnalyzerCollection extends AbstractCollection
{

    /**
     * node name in xml tree
     */
    const NODE_NAME = 'analyzer';

    /**
     * @var string[]
     */
    protected $classMapping = [
        CustomAnalyzer::TYPE => CustomAnalyzer::class,
        KeywordAnalyzer::TYPE => KeywordAnalyzer::class,
        PatternAnalyzer::TYPE => PatternAnalyzer::class,
        SimpleAnalyzer::TYPE => SimpleAnalyzer::class,
        StandardAnalyzer::TYPE => StandardAnalyzer::class,
        StopAnalyzer::TYPE => StopAnalyzer::class,
        WhitespaceAnalyzer::TYPE => WhitespaceAnalyzer::class
    ];
}