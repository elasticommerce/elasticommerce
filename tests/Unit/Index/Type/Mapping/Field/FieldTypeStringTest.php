<?php
#declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Type\Mapping\Field\FieldTypeString;

class FieldTypeStringTest extends TestCase
{

    /**
     * @var FieldTypeString
     */
    protected $fieldType;

    /**
     * set up test case
     */
    protected function setUp()
    {
        $this->fieldType = new FieldTypeString();
    }

    protected function tearDown()
    {
        $this->fieldType = null;
    }

    /**
     * @dataProvider hasMethodDataProvider
     */
    public function testHasMethod($method)
    {
        $this->assertTrue(true === in_array($method, get_class_methods($this->fieldType)));
    }

    /**
     * data provider to check all required methods exists
     *
     * @return array
     */
    public function hasMethodDataProvider()
    {
        return [
            ['setXmlConfig'],
            ['toSchema'],
            ['setType'],
            ['setName']
        ];
    }

    public function testSetName()
    {
        $this->fieldType->setName('foo');
        $this->assertTrue($this->fieldType->getName() == 'foo');
        $this->assertTrue($this->fieldType->getId() == 'foo');
    }

    /**
     * @dataProvider setTypeValidDataProvider
     * @param string $type
     */
    public function testSetTypeValid($type)
    {
        $this->fieldType->setType($type);
        $this->assertEquals($type, $this->fieldType->getType());
        $this->assertEquals($type, $this->fieldType->getData('type'));
    }

    /**
     * data provider to check all valid possible types
     *
     * @return array
     */
    public function setTypeValidDataProvider()
    {
        return [
            ['string']
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeInvalid()
    {
        $this->fieldType->setType('integer');
    }

    public function testInitFromXml()
    {
        $xml = new \SimpleXMLElement('
            <test type="string"><index>no</index><store>false</store></test>
            ');
        $this->fieldType->setXmlConfig($xml);
        $this->assertStringMatchesFormat('no', $this->fieldType->getIndex());
        $this->assertStringMatchesFormat('false', $this->fieldType->getStore());
    }

    public function testInitFromXmlWithFields()
    {
        $xml = new \SimpleXMLElement('
            <test type="string">
                            <fields>
                    <no-decompound type="string">
                        <analyzer>full_text_search_analyzer_no_decompound</analyzer>
                    </no-decompound>
                    <no-stem type="string">
                        <analyzer>default</analyzer>
                    </no-stem>
                </fields>
            </test>
            ');
        $this->fieldType->setXmlConfig($xml);
        $fields = $this->fieldType->getFields();
        $this->assertCount(2, $fields);
        $this->assertEquals('string',$fields->getField('no-decompound')->getType());
        $this->assertEquals('no-decompound',$fields->getField('no-decompound')->getName());
        $this->assertEquals('full_text_search_analyzer_no_decompound',$fields->getField('no-decompound')->getAnalyzer());
        $this->assertEquals('string',$fields->getField('no-stem')->getType());
        $this->assertEquals('no-stem',$fields->getField('no-stem')->getName());
        $this->assertEquals('default',$fields->getField('no-stem')->getAnalyzer());

    }
}