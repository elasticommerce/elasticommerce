<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    ClassicTokenizer
};

class TokenizerClassicTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\ClassicTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new ClassicTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\ClassicTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new ClassicTokenizer();
        $this->assertEquals(['type' => 'classic'], $object->getData());
    }
}