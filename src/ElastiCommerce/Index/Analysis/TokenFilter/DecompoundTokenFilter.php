<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://github.com/jprante/elasticsearch-analysis-decompound
 *
 * Class DecompoundTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class DecompoundTokenFilter extends AbstractTokenFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'decompound';

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