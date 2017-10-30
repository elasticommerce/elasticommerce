<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    SimpleAnalyzer
};

class AnalyzerSimpleTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\SimpleAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new SimpleAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\SimpleAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new SimpleAnalyzer();
        $this->assertEquals(['type' => 'simple'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\SimpleAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/simple_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $this->assertTrue($this->_analyzer->getType() === SimpleAnalyzer::TYPE);
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/simple_analyzer.json',
            json_encode($this->_analyzer->asSchema())
        );
    }
}