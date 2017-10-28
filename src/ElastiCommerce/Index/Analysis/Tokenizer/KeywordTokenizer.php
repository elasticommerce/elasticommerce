<?php
namespace SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer;

/**
 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-tokenizer.html
 *
 * Class KeywordTokenizer
 * @package SmartDevs\ElastiCommerce\Components\Index\Analysis\Tokenizer
 */
class KeywordTokenizer extends AbstractTokenizer
{
    /**
     * type name in declaration
     */
    const TYPE = 'keyword';

    /**
     * add tokenizer type data
     *
     * @param   \SimpleXMLElement $element
     * @throws  \InvalidArgumentException
     * @return  \SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordTokenizer
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        //check we have data
        if (true === property_exists($element, 'buffer_size')) {
            if ((int)$element->buffer_size == 0) {
                throw new \InvalidArgumentException('Keyword Tokenizer attribute "buffer_size" should be greater than zero');
            }
            $this->setData('buffer_size', (int)$element->buffer_size);
        }
        return $this;
    }
}