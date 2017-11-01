<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\CharFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-htmlstrip-charfilter.html
 *
 * Class HTMLStripCharFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\CharFilter
 */
class HTMLStripCharFilter extends AbstractCharFilter
{
    /**
     * type name in declaration
     */
    const TYPE = 'html_strip';

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
        if (true === property_exists($element, 'escaped_tags') && count($element->escaped_tags->children()) > 0) {
            $this->setDataUsingMethod('escaped_tags',
                array_values(
                    array_map(
                        'strval',
                        (array)$element->escaped_tags->children()
                    )
                )
            );
        }
        return $this;
    }
}