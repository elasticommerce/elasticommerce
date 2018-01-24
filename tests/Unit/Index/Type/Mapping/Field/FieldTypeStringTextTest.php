<?php
#declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Type\Mapping\Field\FieldTypeString;

class FieldTypeStringTextTest extends TestCase
{

    /**
     * @var FieldTypeText
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
            ['keyword']
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeInvalid()
    {
        $this->fieldType->setType('integer');
    }

    public function testInitFromXmlAttributesFalse()
    {
        $xml = new \SimpleXMLElement('<test type="text" index="false" store="false"/>');
        $this->fieldType->setXmlConfig($xml);
        $this->assertStringMatchesFormat('false', $this->fieldType->getIndex());
        $this->assertStringMatchesFormat('false', $this->fieldType->getStore());
    }

    public function testInitFromXmlAttributesTrue()
    {
        $xml = new \SimpleXMLElement('<test type="text" index="true" store="true"/>');
        $this->fieldType->setXmlConfig($xml);
        $this->assertStringMatchesFormat('true', $this->fieldType->getIndex());
        $this->assertStringMatchesFormat('true', $this->fieldType->getStore());
    }

    public function testInitFromXmlAttributesIndexTrue()
    {
        $xml = new \SimpleXMLElement('<test type="text" index="true" store="false"/>');
        $this->fieldType->setXmlConfig($xml);
        $this->assertStringMatchesFormat('true', $this->fieldType->getIndex());
        $this->assertStringMatchesFormat('false', $this->fieldType->getStore());
    }

    public function testInitFromXmlAttributesStoreTrue()
    {
        $xml = new \SimpleXMLElement('<test type="text" index="false" store="true"/>');
        $this->fieldType->setXmlConfig($xml);
        $this->assertStringMatchesFormat('false', $this->fieldType->getIndex());
        $this->assertStringMatchesFormat('true', $this->fieldType->getStore());
    }

    public function testInitFromXmlWithFields()
    {
        $xml = new \SimpleXMLElement('
            <test type="text">
                            <fields>
                    <no-decompound type="text">
                        <analyzer>full_text_search_analyzer_no_decompound</analyzer>
                    </no-decompound>
                    <no-stem type="text">
                        <analyzer>default</analyzer>
                    </no-stem>
                </fields>
            </test>
            ');
        $this->fieldType->setXmlConfig($xml);
        $fields = $this->fieldType->getFields();
        $this->assertCount(2, $fields);
        $this->assertEquals('text', $fields->getField('no-decompound')->getType());
        $this->assertEquals('no-decompound', $fields->getField('no-decompound')->getName());
        $this->assertEquals('full_text_search_analyzer_no_decompound', $fields->getField('no-decompound')->getAnalyzer());
        $this->assertEquals('text', $fields->getField('no-stem')->getType());
        $this->assertEquals('no-stem', $fields->getField('no-stem')->getName());
        $this->assertEquals('default', $fields->getField('no-stem')->getAnalyzer());

    }
}