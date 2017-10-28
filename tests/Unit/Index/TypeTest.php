<?php
/**
 * Created by PhpStorm.
 * User: dng
 * Date: 28.10.17
 * Time: 21:38
 */

namespace SmartDevs\ElastiCommerce\Test\Unit\Index;

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Implementor\Index\TypeImplementor;
use SmartDevs\ElastiCommerce\Index\Type;

class TypeTest extends TestCase
{
    /**
     * @var TypeImplementor
     * @SuppressWarnings("PMD.UnusedLocalVariable")
     */
    private $_object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_object = new Type();
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
     * test the idFieldName is "name"
     */
    public function testIdField()
    {
        $this->assertEquals('name', $this->_object->getIdFieldName());
    }

    /**
     * test name setter and getter
     */
    public function testSetName()
    {
        $this->_object->setName('test');
        $this->assertEquals('test', $this->_object->getName());
        $this->assertEquals('test', $this->_object->getData(TypeImplementor::NAME_FIELD_KEY));
        $this->assertEquals('test', $this->_object->getId());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetNameException()
    {
        $this->_object->setName('');
    }

    /**
     * test name setter is fluent
     */
    public function testSetNameFluent()
    {
        $this->assertInstanceOf(TypeImplementor::class, $this->_object->setName('test'));
    }
}
