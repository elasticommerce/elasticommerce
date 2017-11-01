<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-replace-charfilter.html
 *
 * Class PatternReplaceCharFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\CharFilter
 */
class PatternReplaceCharFilter extends AbstractCharFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'pattern_replace';

    /**
     * add char filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractCharFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractCharFilter
    {
        //check we have data
        if (true === property_exists($element, 'pattern')) {
            throw new \InvalidArgumentException('needs to be implemented');
        }
        if (true === property_exists($element, 'replacement')) {
            throw new \InvalidArgumentException('needs to be implemented');
        }
        return $this;
    }
}