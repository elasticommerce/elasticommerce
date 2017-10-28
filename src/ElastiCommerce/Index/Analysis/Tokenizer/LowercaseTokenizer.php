<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lowercase-tokenizer.html
 *
 * Class LowercaseTokenizer
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\Tokenizer
 */
class LowercaseTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'lowercase';

    /**
     * add tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\LowercaseTokenizer
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        return $this;
    }
}