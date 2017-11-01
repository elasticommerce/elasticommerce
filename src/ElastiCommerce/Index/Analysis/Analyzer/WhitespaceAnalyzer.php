<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-whitespace-analyzer.html
 *
 * Class WhitespaceAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
final class WhitespaceAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'whitespace';
}