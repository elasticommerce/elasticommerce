<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    PathHierarchyTokenizer
};

class TokenizerPathHierarchyTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\PathHierarchyTokenizer
     */
    protected $_tokenizer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_tokenizer = new PathHierarchyTokenizer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_tokenizer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\PathHierarchyTokenizer->__construct()
     */
    public function testConstruct()
    {
        $object = new PathHierarchyTokenizer();
        $this->assertEquals(['type' => 'path_hierarchy'], $object->getData());
    }
}