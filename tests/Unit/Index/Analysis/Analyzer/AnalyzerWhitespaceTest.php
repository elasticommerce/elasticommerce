<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    WhitespaceAnalyzer
};

class AnalyzerWhitespaceTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\WhitespaceAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new WhitespaceAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\WhitespaceAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new WhitespaceAnalyzer();
        $this->assertEquals(['type' => 'whitespace'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\WhitespaceAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/whitespace_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $this->assertTrue($this->_analyzer->getType() === WhitespaceAnalyzer::TYPE);
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/whitespace_analyzer.json',
            json_encode($this->_analyzer->toSchema())
        );
    }
}