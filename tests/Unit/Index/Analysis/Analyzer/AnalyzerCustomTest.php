<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\{
    CustomAnalyzer
};

class AnalyzerCustomTest extends TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Analysis\Analyzer\CustomAnalyzer
     */
    protected $_analyzer;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_analyzer = new CustomAnalyzer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_analyzer = null;
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\CustomAnalyzer->__construct()
     */
    public function testConstruct()
    {
        $object = new CustomAnalyzer();
        $this->assertEquals(['type' => 'custom'], $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\CustomAnalyzer->setConfig()
     */
    public function testSetConfig()
    {
        $config = Yaml::parse(file_get_contents(dirname(__FILE__) . '/_files/custom_analyzer.yml'));
        $this->_analyzer->setConfig($config[key($config)]);
        $data = $this->_analyzer->getData();
        $this->assertTrue(array_key_exists('char_filter', $data));
        $this->assertTrue(in_array('my_char_filter', $data['char_filter']));
        $this->assertTrue(array_key_exists('filter', $data));
        $this->assertTrue(in_array('my_filter', $data['filter']));
        $this->assertTrue(in_array('my_other_filter', $data['filter']));
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/custom_analyzer.json',
            json_encode($this->_analyzer->toSchema())
        );
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Index\Analysis\Tokenizer\CustomAnalyzer->setXmlConfig()
     */
    public function testXmlConfig()
    {
        $config = simplexml_load_file(dirname(__FILE__) . '/_files/custom_analyzer.xml');
        $this->_analyzer->setXmlConfig($config);
        $data = $this->_analyzer->getData();
        $this->assertTrue(array_key_exists('char_filter', $data));
        $this->assertTrue(in_array('my_char_filter', $data['char_filter']));
        $this->assertTrue(array_key_exists('filter', $data));
        $this->assertTrue(in_array('my_filter', $data['filter']));
        $this->assertTrue(in_array('my_other_filter', $data['filter']));
        $this->assertJsonStringEqualsJsonFile(
            dirname(__FILE__) . '/_files/custom_analyzer.json',
            json_encode($this->_analyzer->toSchema())
        );
    }
}