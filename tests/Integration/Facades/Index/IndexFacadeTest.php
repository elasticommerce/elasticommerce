<?php
#declare(strict_types = 1);


namespace SmartDevs\ElastiCommerce\Test\Unit\Facades\Index;

use PHPUnit\Framework\TestCase;
use SmartDevs\ElastiCommerce\Facades\IndexFacade;
use SmartDevs\ElastiCommerce\Implementor\Facades\IndexFacadeImplementor;

class IndexFacadeTest extends TestCase
{

    /**
     * @var IndexFacadeImplementor;
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
            $esHost = getenv('ES_HOST');
            if (empty($esHost)) {
                $esHost = 'elasticsearch';
            }
            $this->connection = \Elasticsearch\ClientBuilder::create()
                ->setHosts([sprintf('http://%s:9200', $esHost)])
                ->setRetries(10)
                ->build();
        }
        $this->facade = new IndexFacade($this->connection->indices());
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
            ['existsAlias'],
            ['hasAlias'],
            ['createAlias'],
            ['deleteAliasByIndex'],
            ['deleteAlias'],
            ['rotateAlias']
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
     * @param string $expectedException
     * @param string $indexName
     * @param int $numberOfShards
     * @param int $numberOfReplicas
     */
    public function testIndexCreateException($expectedException, $indexName, $numberOfShards, $numberOfReplicas)
    {
        $this->expectException($expectedException);
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
        $this->assertTrue($this->facade->create($name, 1, 2));
        $this->assertTrue($this->facade->refresh(array($name)));
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
        $this->assertTrue($this->facade->refresh($indices));
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

    /**
     * test index not exists to verify elasticsearch is empty
     */
    public function testAliasNotExists()
    {
        $this->assertFalse($this->facade->existsAlias(uniqid()));
    }

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     */
    public function testAliasCreate()
    {
        $indexName = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexName, 1, 1);
        $this->facade->createAlias($indexName, $aliasName);
        $this->assertTrue($this->facade->existsAlias($aliasName));
    }

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     */
    public function testIndexHasAlias()
    {

        $indexName = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexName, 1, 1);
        $this->facade->createAlias($indexName, $aliasName);
        $this->assertTrue($this->facade->existsAlias($aliasName));
        $this->assertTrue($this->facade->hasAlias($indexName, $aliasName));
    }

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     */
    public function testAliasDeleteWithIndexName()
    {

        $indexName = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexName, 1, 1);
        $this->facade->createAlias($indexName, $aliasName);
        $this->assertTrue($this->facade->existsAlias($aliasName));
        $this->facade->deleteAliasByIndex($aliasName, $indexName);
    }

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     */
    public function testAliasDeleteWithoutIndexName()
    {

        $indexName = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexName, 1, 1);
        $this->facade->createAlias($indexName, $aliasName);
        $this->assertTrue($this->facade->existsAlias($aliasName));
        $this->facade->deleteAlias($aliasName);
    }

    public function testAliasRotateNonExistent()
    {
        $indexNameTarget = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexNameTarget, 1, 1);
        $this->facade->rotateAlias($indexNameTarget, $aliasName);
        $this->assertTrue($this->facade->hasAlias($indexNameTarget, $aliasName));
    }

    public function testAliasRotateWithExistent()
    {
        $indexNameSource = uniqid();
        $indexNameTarget = uniqid();
        $aliasName = uniqid();
        $this->facade->create($indexNameSource, 1, 1);
        $this->facade->create($indexNameTarget, 1, 1);
        $this->facade->createAlias($indexNameSource, $aliasName);
        $this->assertTrue($this->facade->existsAlias($aliasName));
        $this->assertTrue($this->facade->hasAlias($indexNameSource, $aliasName));
        $this->assertFalse($this->facade->hasAlias($indexNameTarget, $aliasName));
        $this->facade->rotateAlias($indexNameTarget, $aliasName);
        $this->assertFalse($this->facade->hasAlias($indexNameSource, $aliasName));
        $this->assertTrue($this->facade->hasAlias($indexNameTarget, $aliasName));
    }
}