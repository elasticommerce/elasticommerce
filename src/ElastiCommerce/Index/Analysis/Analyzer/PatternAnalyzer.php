<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-analyzer.html
 *
 * Class StandardAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
final class PatternAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'pattern';

    /**
     * valid array keys mapped as properties
     *
     * @var array
     */
    protected $validProperties = [
        'pattern',
        'flags',
        'lowercase',
        'stopwords',
        'stopwords_path'
    ];
}