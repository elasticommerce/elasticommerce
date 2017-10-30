<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    KeywordAnalyzer
};

class AnalyzerKeywordTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\KeywordAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new KeywordAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new KeywordAnalyzer();
        $this->assertEquals(['type' => 'keyword'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/keyword_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $this->assertTrue($this->_analyzer->getType() === KeywordAnalyzer::TYPE);
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/keyword_analyzer.json',
            json_encode($this->_analyzer->asSchema())
        );
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\KeywordAnalyzer->setXmlConfig()
     */
    public function testSetXmlConfig()
    {
        $config = simplexml_load_file(dirname(__FILE__) . '/_files/keyword_analyzer.xml');
        $this->_analyzer->setXmlConfig($config);
        $this->assertTrue($this->_analyzer->getType() === KeywordAnalyzer::TYPE);
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/keyword_analyzer.json',
            json_encode($this->_analyzer->asSchema())
        );
    }
}