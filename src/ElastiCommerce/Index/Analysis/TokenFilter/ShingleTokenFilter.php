<?php

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-shingle-tokenfilter.html
 *
 * Class ShingleTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
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
     * @return  AbstractTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter
    {
        return $this;
    }
}