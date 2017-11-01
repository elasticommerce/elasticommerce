<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-standard-analyzer.html
 *
 * Class StandardAnalyzer
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\Analyzer
 */
final class StandardAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'standard';

    /**
     * valid array keys mapped as properties
     *
     * @var array
     */
    protected $validProperties = [
        'max_token_length',
        'stopwords',
        'stopwords_path'
    ];
}