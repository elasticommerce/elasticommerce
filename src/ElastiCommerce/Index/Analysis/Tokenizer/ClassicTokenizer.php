<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-classic-tokenizer.html
 *
 * Class ClassicTokenizer
 * @package SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer
 */
class ClassicTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'classic';

    /**
     * add Tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  AbstractTokenizer
     */
    public function setXmlConfig(\SimpleXMLElement $element): AbstractTokenizer
    {
        //check we have data
        if (true === property_exists($element, 'max_token_length')) {
            if ((int)$element->max_token_length == 0) {
                throw new \InvalidArgumentException('Classic Tokenizer attribute "max_token_length" should be greater than zero');
            }
            $this->setData('max_token_length', (int)$element->max_token_length);
        }
        return $this;
    }
}