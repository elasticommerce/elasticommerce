<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    KeywordTokenizer
};

class TokenizerKeywordTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new KeywordTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new KeywordTokenizer();
        $this->assertEquals(['type' => 'keyword'], $object->getData());
    }
}