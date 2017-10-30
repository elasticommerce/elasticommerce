<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Facades;

use SmartDevs\ElastiCommerce\Implementor\Facades\IndexFacadeImplementor;

class IndexFacade implements IndexFacadeImplementor
{

    /**
     * @var \Elasticsearch\Namespaces\IndicesNamespace
     */
    protected $indicesNamespace = null;

    /**
     * Index constructor.
     * @param \Elasticsearch\Namespaces\IndicesNamespace $indicesNamespace
     */
    public function __construct(\Elasticsearch\Namespaces\IndicesNamespace $indicesNamespace)
    {
        $this->indicesNamespace = $indicesNamespace;
    }

    /**
     * checks an index with the given name exists
     *
     * @param string $indexName
     * @return bool
     */
    public function exists($indexName): bool
    {
        return $this->indicesNamespace->exists(['index' => $indexName]);
    }

    /**
     * create new index
     *
     * @param string $indexName
     * @param int $numberOfShards
     * @param int $numberOfReplicas
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \SmartDevs\ElastiCommerce\Exception
     */
    public function create(string $indexName, int $numberOfShards, int $numberOfReplicas): bool
    {
        if (true === empty($indexName)) {
            throw new \InvalidArgumentException('Parameter $name is empty');
        }
        if ($numberOfShards <= 0) {
            throw new \InvalidArgumentException('Parameter $numberOfShards should be greater then zero.');
        }

        if ($numberOfReplicas <= 0) {
            throw new \InvalidArgumentException('Parameter $numberOfReplicas should be greater then zero.');
        }

        if ($this->exists($indexName)) {
            throw new \SmartDevs\ElastiCommerce\Exception(sprintf('Index "%s" already exists', $indexName));
        }

        $params = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => $numberOfShards,
                    'number_of_replicas' => $numberOfReplicas
                ]
            ]
        ];
        $result = $this->indicesNamespace->create($params);
        return $result['acknowledged'] === true ? true : false;
    }

    /**
     * delete an index
     *
     * @param array $indexNames
     * @return bool
     */
    public function delete(array $indexNames): bool
    {
        if (true === empty($indexNames)) {
            throw new \InvalidArgumentException('Parameter $indexNames is empty array');
        }
        $deleteNames = array_filter($indexNames, function ($indexName) {
            return $this->exists($indexName);
        });

        if (false === empty($deleteNames)) {
            $result = $this->indicesNamespace->delete(['index' => implode(',', $deleteNames)]);
        }
        return isset($result) ? $result['acknowledged'] === true ? true : false : false;
    }

    public function update(): bool
    {
        return false;
    }

    /**
     * refresh and index
     *
     * @param $indexNames
     * @return bool
     */
    public function refresh(array $indexNames): bool
    {
        if (true === empty($indexNames)) {
            throw new \InvalidArgumentException('Parameter $indexNames is empty array');
        }
        $refreshNames = array_filter($indexNames, function ($indexName) {
            return $this->exists($indexName);
        });

        if (false === empty($refreshNames)) {
            $result = $this->indicesNamespace->refresh(['index' => implode(',', $refreshNames)]);
        }
        return isset($result) ? $result['_shards']['successful'] > 0 ? true : false : false;
    }

    /**
     * checks an alias exists
     *
     * @param string $aliasName
     * @return bool
     */
    public function existsAlias(string $aliasName): bool
    {
        return $this->indicesNamespace->existsAlias(['name' => $aliasName]);
    }

    /**
     * checks an index has an specific alias
     *
     * @param string $indexName
     * @param string $indexAlias
     * @return bool
     */
    public function hasAlias(string $indexName, string $indexAlias): bool
    {
        $response = $this->indicesNamespace->getAlias(['name' => $indexAlias]);
        $indexes = array_filter($response, function ($index) use ($indexAlias) {
            if (false === isset($index['aliases'])) {
                return false;
            }
            if (false === is_array($index['aliases']) && true === empty($index['aliases'])) {
                return false;
            }
            return true === isset($index['aliases'][$indexAlias]) ? true : false;
        });
        return array_key_exists($indexName, $indexes);
    }

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     * @return bool
     */
    public function createAlias(string $indexName, string $aliasName): bool
    {
        $result = $this->indicesNamespace->putAlias(['index' => $indexName, 'name' => $aliasName]);
        return $result['acknowledged'] === true ? true : false;
    }

    /**
     * deletes an alias
     *
     * @param string $aliasName
     * @return bool
     */
    public function deleteAliasByIndex(string $aliasName, string $indexName): bool
    {
        if (false === $this->existsAlias($aliasName)) {
            return false;
        }
        if (false === $this->exists($indexName)) {
            return false;
        }
        $result = $this->indicesNamespace->deleteAlias(['index' => $indexName, 'name' => $aliasName]);
        return $result['acknowledged'] === true ? true : false;
    }

    /**
     * deletes an alias
     *
     * @param string $aliasName
     * @return bool
     */
    public function deleteAlias(string $aliasName): bool
    {
        if (false === $this->existsAlias($aliasName)) {
            return false;
        }
        $response = $this->indicesNamespace->getAlias(['name' => $aliasName]);
        $indexes = $this->filterAliasResponseByName($aliasName, $response);

        foreach (array_keys($indexes) as $index) {
            $result = $this->indicesNamespace->deleteAlias(['index' => $index, 'name' => $aliasName]);
        }
        return $result['acknowledged'] === true ? true : false;
    }

    /**
     * rotate an index alias to an new endpoint
     *
     * @param string $indexName
     * @param string $aliasName
     * @return bool
     */
    public function rotateAlias(string $indexName, string $aliasName): bool
    {
        if (false === $this->exists($indexName)) {
            return false;
        }
        $actions = [];
        if (true === $this->existsAlias($aliasName)) {
            $response = $this->indicesNamespace->getAlias(['name' => $aliasName]);
            $indexes = $this->filterAliasResponseByName($aliasName, $response);
            foreach (array_keys($indexes) as $index) {
                $actions[] = ['remove' => ['index' => $index, 'alias' => $aliasName]];
            }
        }
        $actions[] = ['add' => ['index' => $indexName, 'alias' => $aliasName]];
        $parameters['body']['actions'] = $actions;
        $result = $this->indicesNamespace->updateAliases($parameters);
        return $result['acknowledged'] === true ? true : false;
    }

    /**
     * @param string $aliasName
     * @param $response
     * @return array
     */
    protected function filterAliasResponseByName(string $aliasName, $response): array
    {
        return array_filter($response, function ($index) use ($aliasName) {
            if (false === isset($index['aliases'])) {
                return false;
            }
            if (false === is_array($index['aliases']) || true === empty($index['aliases'])) {
                return false;
            }
            return true === isset($index['aliases'][$aliasName]) ? true : false;
        });
    }
}