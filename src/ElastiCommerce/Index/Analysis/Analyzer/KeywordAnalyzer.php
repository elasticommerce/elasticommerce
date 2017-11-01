<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-analyzer.html
 *
 * Class KeywordAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
final class KeywordAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'keyword';
}