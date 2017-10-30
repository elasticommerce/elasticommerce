<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    StandardAnalyzer
};

class AnalyzerStandardTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\StandardAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new StandardAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\StandardAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new StandardAnalyzer();
        $this->assertEquals(['type' => 'standard'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\StandardAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/standard_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $data = $this->_analyzer->getData();
        $this->assertTrue(array_key_exists('stopwords', $data));
        $this->assertTrue(in_array('der', $data['stopwords']));
        $this->assertTrue(in_array('die', $data['stopwords']));
        $this->assertTrue(in_array('das', $data['stopwords']));
        $this->assertTrue(array_key_exists('stopwords_path', $data));
        $this->assertTrue(array_key_exists('max_token_length', $data));
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/standard_analyzer.json',
            json_encode($this->_analyzer->asSchema())
        );
    }
}