<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Analyzer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-custom-analyzer.html
 *
 * Class CustomAnalyzer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Analyzer
 */
final class CustomAnalyzer extends AbstractAnalyzer
{
    /**
     * type name in declaration
     */
    const TYPE = 'custom';

    /**
     * valid array keys mapped as properties
     *
     * @var array
     */
    protected $validProperties = [
        'char_filter',
        'filter',
        'tokenizer',
        'position_increment_gap'
    ];

    /**
     * set char filter
     *
     * @param array $charFilter
     * @return AbstractAnalyzer
     */
    protected function setCharFilter(array $charFilter): AbstractAnalyzer
    {
        $this->_data['char_filter'] = array_map(function ($filterName) {
            return (string)$filterName;
        }, $charFilter);
        return $this;
    }

    /**
     * set filter
     *
     * @param array $filter
     * @return AbstractAnalyzer
     */
    protected function setFilter(array $filter): AbstractAnalyzer
    {
        $this->_data['filter'] = array_map(function ($filterName) {
            return (string)$filterName;
        }, $filter);
        return $this;
    }

    /**
     * set position increment gap
     *
     * @param int $value
     * @return AbstractAnalyzer
     */
    protected function setPositionIncrementGap($value): AbstractAnalyzer
    {
        $this->_data['position_increment_gap'] = (int)$value;
        return $this;
    }
}