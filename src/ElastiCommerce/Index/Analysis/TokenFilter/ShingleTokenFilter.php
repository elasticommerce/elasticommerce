<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-shingle-tokenfilter.html
 *
 * Class ShingleTokenFilter
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\TokenFilter
 */
class ShingleTokenFilter extends AbstractTokenFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'shingle';

    /**
     * add Token Filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter\ShingleTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        return $this;
    }
}