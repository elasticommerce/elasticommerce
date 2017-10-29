<?php
#declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Index\Type\Mapping\Fields\FieldTypeNumeric;

class FieldTypeNumericTest extends TestCase
{

    /**
     * @var FieldTypeNumeric
     */
    protected $fieldType;

    /**
     * set up test case
     */
    protected function setUp()
    {
        $this->fieldType = new FieldTypeNumeric();
    }

    protected function tearDown()
    {
        $this->fieldType = null;
    }

    /**
     * @dataProvider hasMethodDataProvider
     */
    public function testhasMethod($method)
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
            ['long'],
            ['integer'],
            ['short'],
            ['byte'],
            ['double'],
            ['float']
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeInvalid()
    {
        $this->fieldType->setType('string');
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
}