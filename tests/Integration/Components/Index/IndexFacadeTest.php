<?php
#declare(strict_types = 1);

use SmartDevs\ElastiCommerce\Index\Index;

class IndexFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Index;
     */
    protected $facade;

    /**
     * @var \Elasticsearch\Client
     */
    protected $connection = null;

    /**
     * set up test case
     */
    protected function setUp()
    {
        if (null == $this->connection) {
            $this->connection = \Elasticsearch\ClientBuilder::create()
                ->setHosts(['http://elasticsearch:9200'])
                ->setRetries(10)
                ->build();
        }
        $this->facade = new Index($this->connection->indices());
        $this->connection->indices()->delete(['index' => '_all']);
    }

    protected function tearDown()
    {
        $this->connection->indices()->delete(['index' => '_all']);
    }


    /**
     * @dataProvider methodExistsDataProvider
     */
    public function testMethodExists($method)
    {
        $this->assertTrue(true === in_array($method, get_class_methods($this->facade)));
    }

    /**
     * data provider to check all required methods exists
     *
     * @return array
     */
    public function methodExistsDataProvider()
    {
        return [
            ['create'],
            ['update'],
            ['delete'],
            ['exists'],
            ['refresh'],
            #['rotateAlias'],
            #['addAlias'],
            #['removeAlias'],
            #['hasAlias'],
        ];
    }

    /**
     * test index not exists to verify elasticsearch is empty
     */
    public function testIndexNotExists()
    {
        $this->assertFalse($this->facade->exists(uniqid()));
    }

    /**
     * test index creation
     */
    public function testIndexCreate()
    {
        $name = uniqid();
        $this->facade->create($name, 1, 2);
        $this->assertTrue($this->facade->exists($name));
    }

    /**
     * @dataProvider indexCreateExceptionDataProvider
     *
     * @param string $expectedException
     * @param string $indexName
     * @param int $numberOfShards
     * @param int $numberOfReplicas
     */
    public function testIndexCreateException($expectedException, $indexName, $numberOfShards, $numberOfReplicas)
    {
        $this->setExpectedException($expectedException);
        $this->facade->create($indexName, $numberOfShards, $numberOfReplicas);
    }

    /**
     * dataprovider for testIndexCreateException
     *
     * @return array
     */
    public function indexCreateExceptionDataProvider()
    {
        return [
            ['InvalidArgumentException', uniqid(), 0, 0],
            ['InvalidArgumentException', uniqid(), 0, 1],
            ['InvalidArgumentException', uniqid(), 1, 0],
            ['InvalidArgumentException', '', 1, 1],
        ];
    }

    public function testIndexDeleteSimple()
    {
        $name = uniqid();
        $this->facade->create($name, 1, 2);
        $this->assertTrue($this->facade->exists($name));
        $this->facade->delete(array($name));
        $this->assertFalse($this->facade->exists($name));
    }

    /**
     * test to delete multiple indexes
     */
    public function testIndexDeleteMultiple()
    {
        $indices = [uniqid(), uniqid(), uniqid()];
        foreach ($indices as $name) {
            $this->facade->create($name, 1, 2);
        }
        foreach ($indices as $name) {
            $this->assertTrue($this->facade->exists($name));
        }
        $this->facade->delete($indices);
        foreach ($indices as $name) {
            $this->assertFalse($this->facade->exists($name));
        }
    }

    /**
     * test to delete multiple indexes
     */
    public function testIndexDeleteMultipleAndNotExistent()
    {
        $indices = [uniqid(), uniqid(), uniqid(), uniqid()];
        foreach ($indices as $k => $name) {
            if ($k % 2) {
                continue;
            }
            $this->facade->create($name, 1, 2);
        }
        foreach ($indices as $k => $name) {
            if ($k % 2) {
                $this->assertFalse($this->facade->exists($name));
            } else {
                $this->assertTrue($this->facade->exists($name));
            }
        }
        $this->facade->delete($indices);
        foreach ($indices as $name) {
            $this->assertFalse($this->facade->exists($name));
        }
    }

    /**
     * simple test for refresh index
     */
    public function testIndexRefreshSimple()
    {
        $name = uniqid();
        $this->facade->create($name, 1, 2);
        $this->facade->refresh(array($name));
    }

    /**
     * test to refresh multiple indexes
     */
    public function testIndexRefreshMultiple()
    {
        $indices = [uniqid(), uniqid(), uniqid()];
        foreach ($indices as $name) {
            $this->facade->create($name, 1, 2);
        }
        $this->facade->refresh($indices);
    }

    /**
     * test to delete multiple indexes
     */
    public function testIndexRefreshMultipleAndNotExistent()
    {
        $indices = [uniqid(), uniqid(), uniqid(), uniqid()];
        foreach ($indices as $k => $name) {
            if ($k % 2) {
                continue;
            }
            $this->facade->create($name, 1, 2);
        }
        foreach ($indices as $k => $name) {
            if ($k % 2) {
                $this->assertFalse($this->facade->exists($name));
            } else {
                $this->assertTrue($this->facade->exists($name));
            }
        }
        $this->facade->refresh($indices);
    }
}