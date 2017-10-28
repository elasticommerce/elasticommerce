<?php
/**
 * Created by PhpStorm.
 * User: dng
 * Date: 28.10.17
 * Time: 21:38
 */

namespace SmartDevs\ElastiCommerce\Test\Unit\Index;

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Implementor\Index\{
    TypeCollectionImplementor, TypeImplementor
};
use SmartDevs\ElastiCommerce\Index\{
    TypeCollection
};

class TypeCollectionTest extends TestCase
{
    /**
     * @var TypeCollectionImplementor
     */
    protected $_object;

    /**
     * set up test case
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_object = new TypeCollection();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->_object = null;
    }

    /**
     * Tests TypeCollection->__construct()
     */
    public function testConstruct()
    {
        $object = new TypeCollection();
        $this->assertEquals(0, $object->count());
    }

    /**
     * TypeCollection->getNewType()
     */
    public function testGetNewType()
    {
        $instance = $this->_object->getNewType();
        $this->assertInstanceOf(TypeImplementor::class, $instance);
    }

    /**
     * TypeCollection->addType()
     */
    public function testAddTypeType()
    {
        $instance = $this->_object->getNewType();
        $instance->setName('test');
        $this->_object->addType($instance);
        $this->assertInstanceOf(TypeImplementor::class, $this->_object->getType('test'));
        $this->assertCount(1, $this->_object->getItems());
        $this->assertInstanceOf(TypeImplementor::class, $instance);
    }

    /**
     * TypeCollection->getType()
     *
     * @expectedException \TypeError
     */
    public function testGetTypeError()
    {
        $this->_object->getType('test');
    }


}
