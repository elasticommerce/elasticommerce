<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    PatternAnalyzer
};

class AnalyzerPatternTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\PatternAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new PatternAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\PatternAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new PatternAnalyzer();
        $this->assertEquals(['type' => 'pattern'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\PatternAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/pattern_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $data = $this->_analyzer->getData();
        $this->assertTrue(array_key_exists('stopwords', $data));
        $this->assertTrue(in_array('der', $data['stopwords']));
        $this->assertTrue(in_array('die', $data['stopwords']));
        $this->assertTrue(in_array('das', $data['stopwords']));
        $this->assertTrue(array_key_exists('stopwords_path', $data));
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/pattern_analyzer.json',
            json_encode($this->_analyzer->toSchema())
        );
    }
}