<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-mapping-charfilter.html
 *
 * Class MappingCharFilter
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\CharFilter
 */
class MappingCharFilter extends AbstractCharFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'mapping';

    /**
     * add char filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\CharFilter\MappingCharFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        //check we have data
        if (true === property_exists($element, 'mappings')) {
            throw new \Exception('needs to be implemented');
        }
        return $this;
    }
}