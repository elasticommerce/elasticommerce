<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-snowball-tokenfilter.html
 *
 * Class SnowballTokenFilter
 * @package SmartDevs\ElastiCommerce\Index\Analysis\TokenFilter
 */
class SnowballTokenFilter extends AbstractTokenFilter
{

    protected $validLanguages = [
        "Armenian",
        "Basque",
        "Catalan",
        "Danish",
        "Dutch",
        "English",
        "Finnish",
        "French",
        "German",
        "German2",
        "Hungarian",
        "Italian",
        "Kp",
        "Lithuanian",
        "Lovins",
        "Norwegian",
        "Porter",
        "Portuguese",
        "Romanian",
        "Russian",
        "Spanish",
        "Swedish",
        "Turkish"
    ];

    /**
     * type name in declaration
     */
    const TYPE = 'snowball';

    /**
     * add Token Filter type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenFilter
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenFilter
    {
        if (true === property_exists($element, 'language')) {
            if (true === empty($element->language)) {
                throw new \InvalidArgumentException('Snowball token filter should contain one element');
            }
            $this->setData('language', (string)$element->language);
        }
        return $this;
    }
}