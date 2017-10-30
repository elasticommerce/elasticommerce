<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    WhitespaceTokenizer
};

class TokenizerWhitespaceTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\WhitespaceTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new WhitespaceTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\WhitespaceTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new WhitespaceTokenizer();
        $this->assertEquals(['type' => 'whitespace'], $object->getData());
    }
}