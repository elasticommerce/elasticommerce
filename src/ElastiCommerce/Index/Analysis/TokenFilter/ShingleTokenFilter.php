<?php
declare(strict_types=1);

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
        if (true === property_exists($element, 'min_shingle_size')) {
            if ((int)$element->min_shingle_size == 0) {
                throw new \InvalidArgumentException('Shingle Token Filter attribute "min_shingle_size" should be greater than zero');
            }
            $this->setData('min_shingle_size', (int)$element->min_shingle_size);
        }
        if (true === property_exists($element, 'min_shingle_size')) {
            if ((int)$element->min_shingle_size == 0) {
                throw new \InvalidArgumentException('Shingle Token Filter attribute "min_shingle_size" should be greater than zero');
            }
            $this->setData('min_shingle_size', (int)$element->min_shingle_size);
        }
        return $this;
    }
}