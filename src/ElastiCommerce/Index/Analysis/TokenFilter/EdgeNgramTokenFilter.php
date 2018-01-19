<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-edgengram-tokenfilter.html
 *
 * Class EdgeNgramTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class EdgeNgramTokenFilter extends AbstractTokenFilter
{

    /**
     * type name in declaration
     */
    const TYPE = 'edge_ngram';

    /**
     * add Token Filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter
    {
        if (true === property_exists($element, 'min_gram')) {
            if ((int)$element->min_gram == 0) {
                throw new \InvalidArgumentException('Edge n-gram Token Filter attribute "min_gram" should be greater than zero');
            }
            $this->setData('min_gram', (int)$element->min_gram);
        }
        if (true === property_exists($element, 'max_gram')) {
            if ((int)$element->max_gram == 0) {
                throw new \InvalidArgumentException('Edge n-gram Token Filter attribute "max_gram" should be greater than zero');
            }
            $this->setData('max_gram', (int)$element->max_gram);
        }
        return $this;
    }
}