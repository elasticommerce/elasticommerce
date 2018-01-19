<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-word-delimiter-tokenfilter.html
 *
 * Class WordDelimiterTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class WordDelimiterTokenFilter extends AbstractTokenFilter
{

    protected $validLanguages = [
        "_arabic_",
        "_armenian_",
        "_basque_",
        "_brazilian_",
        "_bulgarian_",
        "_catalan_",
        "_czech_",
        "_danish_",
        "_dutch_",
        "_english_",
        "_finnish_",
        "_french_",
        "_galician_",
        "_german_",
        "_greek_",
        "_hindi_",
        "_hungarian_",
        "_indonesian_",
        "_irish_",
        "_italian_",
        "_latvian_",
        "_norwegian_",
        "_persian_",
        "_portuguese_",
        "_romanian_",
        "_russian_",
        "_sorani_",
        "_spanish_",
        "_swedish_",
        "_thai_",
        "_turkish_",
        "_none_"
    ];

    /**
     * type name in declaration
     */
    const TYPE = 'word_delimiter';

    /**
     * add Token Filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter
    {
        if (true === property_exists($element, 'stopwords')) {
            if (true === empty($element->stopwords)) {
                throw new \InvalidArgumentException('Stop token filter should contain one element');
            }
            $this->setData('stopwords', (string)$element->stopwords);
        }
        return $this;
    }
}