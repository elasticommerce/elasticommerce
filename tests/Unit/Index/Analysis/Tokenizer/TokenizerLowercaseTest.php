<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    LowercaseTokenizer
};

class TokenizerLowercaseTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\LowercaseTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new LowercaseTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\LowercaseTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new LowercaseTokenizer();
        $this->assertEquals(['type' => 'lowercase'], $object->getData());
    }
}