<?php
#declare(strict_types = 1);

use SmartDevs\ElastiCommerce\Index\Index;

class IndexFacadeAliasTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SmartDevs\ElastiCommerce\Index\Index
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
        $this->facade->hasAlias($indexName, $aliasName);
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
        $this->assertTrue($this->facade->hasAlias($indexNameTarget,$aliasName));
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
        $this->assertTrue($this->facade->hasAlias($indexNameSource,$aliasName));
        $this->assertFalse($this->facade->hasAlias($indexNameTarget,$aliasName));
        $this->facade->rotateAlias($indexNameTarget, $aliasName);
        $this->assertFalse($this->facade->hasAlias($indexNameSource,$aliasName));
        $this->assertTrue($this->facade->hasAlias($indexNameTarget,$aliasName));
    }
}