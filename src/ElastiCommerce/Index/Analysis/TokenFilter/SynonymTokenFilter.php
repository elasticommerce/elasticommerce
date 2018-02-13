<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-synonym-tokenfilter.html
 *
 * Class ShingleTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class SynonymTokenFilter extends AbstractTokenFilter
{

    /**
     * type name in declaration
     */
    const TYPE = 'synonym';

    /**
     * add Token Filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter
    {
        if (true === property_exists($element, 'synonyms_path')) {
            #if (false === (bool)preg_match('(?:[a-zA-Z]:(\|/)|file://|\\|.(/|\)|/)([^,\/:*\?\<>\"\|]+(\|/){0,1})', (string)$element->synonyms_path)) {
            #    throw new \InvalidArgumentException('Synonym Token Filter attribute "synonyms_path" should be an valid unix path');
            #}
            $this->setData('synonyms_path', (string)$element->synonyms_path);
        } else {
            throw new \InvalidArgumentException('Synonym Token Filter attribute "synonyms_path" is required');
        }
        return $this;
    }
}