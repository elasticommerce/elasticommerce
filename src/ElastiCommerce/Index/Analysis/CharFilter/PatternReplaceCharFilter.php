<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-pattern-replace-charfilter.html
 *
 * Class PatternReplaceCharFilter
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\CharFilter
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
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\CharFilter\PatternReplaceCharFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        //check we have data
        if (true === property_exists($element, 'pattern')) {
            throw new \Exception('needs to be implemented');
        }
        if (true === property_exists($element, 'replacement')) {
            throw new \Exception('needs to be implemented');
        }
        return $this;
    }
}