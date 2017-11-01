<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lowercase-tokenizer.html
 *
 * Class LowercaseTokenizer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer
 */
class LowercaseTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'lowercase';

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