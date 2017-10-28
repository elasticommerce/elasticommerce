<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-analyzer.html
 *
 * Class StandardAnalyzer
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\Analyzer
 */
final class StopAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'stop';

    /**
     * valid array keys mapped as properties
     *
     * @var array
     */
    protected $validProperties = [
        'stopwords',
        'stopwords_path'
    ];
}