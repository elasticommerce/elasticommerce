<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-whitespace-tokenizer.html
 *
 * Class WhitespaceTokenizer
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\Tokenizer
 */
class WhitespaceTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'whitespace';

    /**
     * add tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\WhitespaceTokenizer
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        return $this;
    }
}