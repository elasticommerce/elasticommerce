<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    StandardTokenizer
};

class TokenizerStandardTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\StandardTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new StandardTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\StandardTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new StandardTokenizer();
        $this->assertEquals(['type' => 'standard'], $object->getData());
    }
}