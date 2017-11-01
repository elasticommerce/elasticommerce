<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-standard-tokenfilter.html
 *
 * Class StandardTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class StandardTokenFilter extends AbstractTokenFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'standard';

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