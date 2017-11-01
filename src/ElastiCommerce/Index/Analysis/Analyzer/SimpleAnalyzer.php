<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-simple-analyzer.html
 *
 * Class SimpleAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
final class SimpleAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'simple';
}