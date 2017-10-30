<?php

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Analysis\{
    TokenizerCollection
};
use \SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\{
    ClassicTokenizer,
    KeywordTokenizer,
    LowercaseTokenizer,
    StandardTokenizer,
    WhitespaceTokenizer,
    PathHierarchyTokenizer
};

class TokenizerCollectionTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\TokenizerCollection
     */
    protected $_object;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_object = new TokenizerCollection();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_object = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\TokenizerCollection->__construct()
     */
    public function testConstruct()
    {
        $object = new TokenizerCollection();
        $this->assertEquals(0, $object->count());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\TokenizerCollection->getTokenizerInstance()
     * @dataProvider ProviderTokenizerMappingLookup
     */
    public function testTokenizerMappingLookup($input, $expectedOutput)
    {
        $refObject = new \ReflectionObject($this->_object);
        $refMethod = $refObject->getMethod('getClassNamefromMapping');
        $refMethod->setAccessible(true);
        $output = $refMethod->invoke($this->_object, $input);
        $this->assertEquals($expectedOutput, $output);

    }

    /**
     * provider for testTokenizerMappingLookup
     */
    public function ProviderTokenizerMappingLookup()
    {
        return [
            'Test Classic' => ['classic', ClassicTokenizer::class],
            'Test Keyword' => ['keyword', KeywordTokenizer::class],
            'Test Lowercase' => ['lowercase', LowercaseTokenizer::class],
            'Test Standard' => ['standard', StandardTokenizer::class],
            'Test Whitespace' => ['whitespace', WhitespaceTokenizer::class],
            'Test Path Hierarchy' => ['path_hierarchy', PathHierarchyTokenizer::class],
        ];
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\TokenizerCollection->addItem()
     */
    public function testAddItem()
    {
        $tokenizer = new StandardTokenizer();
        $tokenizer->setName('test');
        $this->_object->addItem($tokenizer);
        $this->assertEquals(1, $this->_object->count());
        $this->_object->setItem($tokenizer);
        $this->assertEquals(1, $this->_object->count());
    }


    public function testInitFromXml()
    {
        $xml = simplexml_load_string('
        <schema>
            <tokenizer>
                <test_whitespace type="whitespace"/>
            </tokenizer>
        </schema>');
        $this->_object->setXmlConfig($xml);

        $this->assertTrue($this->_object->getItemById('test_whitespace') instanceof WhitespaceTokenizer);
        $this->assertTrue($this->_object->count() == 1);
    }
}