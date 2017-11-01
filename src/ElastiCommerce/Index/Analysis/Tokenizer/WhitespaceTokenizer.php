<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-whitespace-tokenizer.html
 *
 * Class WhitespaceTokenizer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer
 */
class WhitespaceTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'whitespace';

    /**
     * add Tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenizer
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenizer
    {
        return $this;
    }
}