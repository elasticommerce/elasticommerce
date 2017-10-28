<?php

namespace SmartDevs\Util\Test\Unit\Util\Data;

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Util\Data\DataObject;

class DataObjectTest extends TestCase
{
    /**
     * @var DataObject
     * @SuppressWarnings("PMD.UnusedLocalVariable")
     */
    private $_object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_object = new DataObject();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->_object = null;
        parent::tearDown();
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->__construct()
     */
    public function testConstruct()
    {
        $object = new DataObject();
        $this->assertEquals([], $object->getData());

        $data = ['test' => 'test'];
        $object = new DataObject($data);
        $this->assertEquals($data, $object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->addData()
     */
    public function testAddData()
    {
        $this->_object->addData(['test' => 'value']);
        $this->assertEquals('value', $this->_object->getData('test'));

        $this->_object->addData(['test' => 'value1']);
        $this->assertEquals('value1', $this->_object->getData('test'));

        $this->_object->addData(['test2' => 'value2']);
        $this->assertEquals(['test' => 'value1', 'test2' => 'value2'], $this->_object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->setData()
     */
    public function testSetData()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 3];
        $this->_object->setData($data);
        $this->assertEquals($data, $this->_object->getData());

        $data['key1'] = 1;
        $this->_object->setData('key1', 1);
        $this->assertEquals($data, $this->_object->getData());

        $this->_object->setData('key1');
        $data['key1'] = null;
        $this->assertEquals($data, $this->_object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->unsetData()
     */
    public function testUnsetData()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 3, 'key4' => 4];
        $this->_object->setData($data);

        $this->_object->unsetData('key1');
        unset($data['key1']);
        $this->assertEquals($data, $this->_object->getData());

        $this->_object->unsetData(['key2', 'key3']);
        unset($data['key2']);
        unset($data['key3']);
        $this->assertEquals($data, $this->_object->getData());

        $this->_object->unsetData();
        $this->assertEquals([], $this->_object->getData());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->getData()
     */
    public function testGetData()
    {
        $data = [
            'key1' => 'value1',
            'key2' => [
                'subkey2.1' => 'value2.1',
                'subkey2.2' => 'multiline' . PHP_EOL . 'string',
                'subkey2.3' => new DataObject(['test_key' => 'test_value']),
            ],
            'key3' => 5,
        ];
        foreach ($data as $key => $value) {
            $this->_object->setData($key, $value);
        }
        $this->assertEquals($data, $this->_object->getData());
        $this->assertEquals('value1', $this->_object->getData('key1'));
        $this->assertEquals('value2.1', $this->_object->getData('key2/subkey2.1'));
        $this->assertEquals('value2.1', $this->_object->getData('key2', 'subkey2.1'));
//        $this->assertEquals('string', $this->_object->getData('key2/subkey2.2', 1));
//        $this->assertEquals('test_value', $this->_object->getData('key2/subkey2.3', 'test_key'));
        $this->assertNull($this->_object->getData('key3', 'test_key'));
    }

//    public function testGetDataByPath()
//    {
//        $data = [
//            'key1' => 'value1',
//            'key2' => [
//                'subkey2.1' => 'value2.1',
//                'subkey2.2' => 'multiline
//string',
//                'subkey2.3' => new DataObject(['test_key' => 'test_value']),
//            ],
//        ];
//        foreach ($data as $key => $value) {
//            $this->_object->setData($key, $value);
//        }
//        $this->assertEquals('value1', $this->_object->getDataByPath('key1'));
//        $this->assertEquals('value2.1', $this->_object->getDataByPath('key2/subkey2.1'));
//        $this->assertEquals('test_value', $this->_object->getDataByPath('key2/subkey2.3/test_key'));
//        $this->assertNull($this->_object->getDataByPath('empty'));
//        $this->assertNull($this->_object->getDataByPath('empty/path'));
//    }

//    public function testGetDataByKey()
//    {
//        $this->_object->setData('key', 'value');
//        $this->assertEquals('value', $this->_object->getDataByKey('key'));
//        $this->assertNull($this->_object->getDataByKey('empty'));
//    }

//    /**
//     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->setDataUsingMethod()
//     * @PHPuni
//     */
//    public function testSetGetDataUsingMethod()
//    {
//        $mock = $this->getMock(DataObject::class, ['setTestData', 'getTestData']);
//        $mock->expects($this->once())->method('setTestData')->with($this->equalTo('data'));
//        $mock->expects($this->once())->method('getTestData');
//
//        $mock->setDataUsingMethod('test_data', 'data');
//        $mock->getDataUsingMethod('test_data');
//    }

//    /**
//     * Test documenting current behaviour of getDataUsingMethod
//     * _underscore assumes an underscore before any digit
//     */
//    public function testGetDataUsingMethodWithoutUnderscore()
//    {
//        $this->_object->setData('key_1', 'value1');
//        $this->assertTrue($this->_object->hasData('key_1'));
//        $this->assertEquals('value1', $this->_object->getDataUsingMethod('key_1'));
//
//        $this->_object->setData('key2', 'value2');
//        $this->assertEquals('value2', $this->_object->getData('key2'));
//        $this->assertEquals(null, $this->_object->getKey2());
//        $this->assertEquals(null, $this->_object->getDataUsingMethod('key2'));
//    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->hasData()
     */
    public function testHasData()
    {
        $this->assertFalse($this->_object->hasData());
        $this->assertFalse($this->_object->hasData('key'));
        $this->_object->setData('key', 'value');
        $this->assertTrue($this->_object->hasData('key'));
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->toArray()
     */
    public function testToArray()
    {
        $this->assertEquals([], $this->_object->toArray());
        $this->assertEquals(['key' => null], $this->_object->toArray(['key']));
        $this->_object->setData('key1', 'value1');
        $this->_object->setData('key2', 'value2');
        $this->assertEquals(['key1' => 'value1'], $this->_object->toArray(['key1']));
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->toXml()
     */
    public function testToXml()
    {
        $this->_object->setData('key1', 'value1');
        $this->_object->setData('key2', 'value2');
        $xml = '<item>
<key1><![CDATA[value1]]></key1>
<key2><![CDATA[value2]]></key2>
</item>
';
        $this->assertEquals($xml, $this->_object->toXml());

        $xml = '<item>
<key2><![CDATA[value2]]></key2>
</item>
';
        $this->assertEquals($xml, $this->_object->toXml(['key2']));

        $xml = '<my_item>
<key1><![CDATA[value1]]></key1>
<key2><![CDATA[value2]]></key2>
</my_item>
';
        $this->assertEquals($xml, $this->_object->toXml([], 'my_item'));

        $xml = '<key1><![CDATA[value1]]></key1>
<key2><![CDATA[value2]]></key2>
';
        $this->assertEquals($xml, $this->_object->toXml([], false));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<item>
<key1><![CDATA[value1]]></key1>
<key2><![CDATA[value2]]></key2>
</item>
';
        $this->assertEquals($xml, $this->_object->toXml([], 'item', true));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<item>
<key1>value1</key1>
<key2>value2</key2>
</item>
';
        $this->assertEquals($xml, $this->_object->toXml([], 'item', true, false));
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->toJson()
     */
    public function testToJson()
    {
        $this->_object->setData('key1', 'value1');
        $this->_object->setData('key2', 'value2');
        $this->assertEquals('{"key1":"value1","key2":"value2"}', $this->_object->toJson());
        $this->assertEquals('{"key1":"value1"}', $this->_object->toJson(['key1']));
        $this->assertEquals('{"key1":"value1","key":null}', $this->_object->toJson(['key1', 'key']));
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->__get()
     * @expectedException BadMethodCallException
     */
    public function testGetSet()
    {
        $this->_object->test = 'test';
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->isEmpty()
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->_object->isEmpty());
        $this->_object->setData('test', 'test');
        $this->assertFalse($this->_object->isEmpty());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->serialize()
     */
    public function testSerialize()
    {
        $this->_object->setData('key1', 'value1');
        $this->_object->setData('key2', 'value2');
        $this->assertEquals('a:2:{s:8:"id_field";N;s:4:"data";a:2:{s:4:"key1";s:6:"value1";s:4:"key2";s:6:"value2";}}', $this->_object->serialize());
        $this->assertEquals(
            'a:2:{s:8:"id_field";N;s:4:"data";a:2:{s:4:"key1";s:6:"value1";s:4:"key2";s:6:"value2";}}',
            $this->_object->serialize(['key', 'key1', 'key2'], ':', '_', '\'')
        );
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->offsetSet()
     */
    public function testOffset()
    {
        $this->_object->offsetSet('key1', 'value1');
        $this->assertTrue($this->_object->offsetExists('key1'));
        $this->assertFalse($this->_object->offsetExists('key2'));

        $this->assertEquals('value1', $this->_object->offsetGet('key1'));
        $this->assertNull($this->_object->offsetGet('key2'));
        $this->_object->offsetUnset('key1');
        $this->assertFalse($this->_object->offsetExists('key1'));
    }

    /**
     * Tests _underscore method directly
     *
     * @dataProvider underscoreDataProvider
     */
    public function testUnderscore($input, $expectedOutput)
    {
        $refObject = new \ReflectionObject($this->_object);
        $refMethod = $refObject->getMethod('_underscore');
        $refMethod->setAccessible(true);
        $output = $refMethod->invoke($this->_object, $input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function underscoreDataProvider()
    {
        return [
            'Test 1' => ['Stone1Color', 'stone1_color'],
            'Test 2' => ['StoneColor', 'stone_color'],
            'Test 3' => ['StoneToXml', 'stone_to_xml'],
            'Test 4' => ['1StoneColor', '1_stone_color'],
            'Test 5' => ['getCcLast4', 'get_cc_last4'],
            'Test 6' => ['99Bottles', '99_bottles'],
            'Test 7' => ['XApiLogin', 'x_api_login']
        ];
    }


    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->setIdFieldName()
     */
    public function testSetIdFieldName()
    {
        $this->assertTrue(null === $this->_object->getIdFieldName());
        $this->_object->setIdFieldName('name');
        $this->assertTrue('name' === $this->_object->getIdFieldName());
    }

    /**
     * Tests SmartDevs\ElastiCommerce\Util\Data\DataObject->setIdFieldName()
     */
    public function testGetId()
    {
        $object = new DataObject(['name' => 'test']);
        $object->setIdFieldName('name');
        $this->assertTrue($object->getId() === 'test');
    }
}