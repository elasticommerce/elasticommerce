<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-mapping-charfilter.html
 *
 * Class MappingCharFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\CharFilter
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
     * @param   \SimpleXMLElement $xml
     * @throws  \InvalidArgumentException
     * @return  AbstractCharFilter
     */
    public function setXmlConfig(\SimpleXMLElement $xml): AbstractCharFilter
    {
        //check we have data
        if (true === property_exists($xml, 'mappings')) {
            throw new \InvalidArgumentException('needs to be implemented');
        }
        return $this;
    }
}