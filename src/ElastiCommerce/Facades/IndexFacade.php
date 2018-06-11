<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Facades;

use SmartDevs\ElastiCommerce\Config\IndexConfig;
use SmartDevs\ElastiCommerce\Implementor\Index\Type\MappingImplementor;
use SmartDevs\ElastiCommerce\Index\Settings;

class IndexFacade
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
     * @param IndexConfig $indexConfig
     *
     * @return bool
     * @throws \InvalidArgumentException
     * @throws \SmartDevs\ElastiCommerce\Exception
     */
    public function create(
        string $indexName,
        Settings $indexSettings,
        array $dynamicTemplates = []
    ): bool
    {
        if (true === empty($indexName)) {
            throw new \InvalidArgumentException('Parameter $name is empty');
        }

        if ($this->exists($indexName)) {
            throw new \SmartDevs\ElastiCommerce\Exception(sprintf('Index "%s" already exists', $indexName));
        }

        $params = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'refresh_interval' => "10s",
                    'number_of_shards' => $indexSettings->getNumberOfShards(),
                    'number_of_replicas' => $indexSettings->getNumberOfReplicas(),
                    'index.mapping.total_fields.limit' => $indexSettings->getTotalFieldsLimit(),
                    'analysis' => [
                        'analyzer' => $indexSettings->getAnalyzer()->toSchema(),
                        'char_filter' => $indexSettings->getCharFilter()->toSchema(),
                        'filter' => $indexSettings->getTokenFilter()->toSchema(),
                        'tokenizer' => $indexSettings->getTokenizer()->toSchema()
                    ]
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
     * get an array of orphaned indexes which don't has an alias
     *
     * @param string $prefix
     * @return array
     */
    public function getOrphanedIndices(string $prefix): array
    {
        if (true === empty($prefix)) {
            throw new \InvalidArgumentException('Parameter $prefix is empty');
        }
        $searchPrefix = $prefix . '*';
        $response = $this->indicesNamespace->get(['index' => $searchPrefix]);
        $indexes = array_filter($response, function ($index) use ($prefix) {
            if (false === isset($index['aliases'])) {
                return true;
            }
            if (true === is_array($index['aliases']) && true === empty($index['aliases'])) {
                return true;
            }
            return false;
        });
        return array_keys($indexes);
    }

    /**
     * deletes indexes with given prefix which don't has an alias
     * @param string $prefix
     * @return bool
     */
    public function deleteOrphanedIndices(string $prefix): bool
    {
        if (true === empty($prefix)) {
            throw new \InvalidArgumentException('Parameter $prefix is empty');
        }
        $indexes = $this->getOrphanedIndices($prefix);
        if (true === is_array($indexes) && count($indexes) > 0) {
            return $this->delete($indexes);
        }
        return true;
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
     * deletes an alias by an given index name
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

    public function setMapping(string $indexName, string $indexType, MappingImplementor $mapping): bool
    {
        if (false === $this->exists($indexName)) {
            throw new \SmartDevs\ElastiCommerce\Exception(sprintf('Index "%s" doesn\'t exists', $indexName));
        }
        $this->indicesNamespace->putMapping([
            'index' => $indexName,
            'type' => $indexType,
            'body' => [
                $indexType => [
                    '_source' => [
                        'enabled' => true
                    ],
                    '_all' => [
                        'enabled' => false
                    ],
                    'dynamic_templates' => $mapping->getDynamicTemplates()->toSchema(),
                    'properties' => $mapping->getMapping()->toSchema()
                ]
            ]
        ]);
#        $this->indicesNamespace->putTemplate([
#            'name' => 'elasticommerce',
#            'body' => [
#                "template" => "elasticommerce-*",
#                'mappings' => [
#                    $indexType => [
#                        'dynamic_templates' => $mapping->getDynamicTemplates()->toSchema()
#                    ]
#                ]
#            ]
#        ]);
        return false;
    }

    public function setAnalysis(string $indexName, array $analyzer, array $charfilter, array $tokenfilter, array $tokenizer): bool
    {
        if (false === $this->exists($indexName)) {
            throw new \SmartDevs\ElastiCommerce\Exception(sprintf('Index "%s" doesn\'t exists', $indexName));
        }
        $result = $this->indicesNamespace->putSettings(
            ['index' => $indexName,
                'body' => ['settings' =>
                    ['analysis' => [
                        'analyzer' => $analyzer,
                        'char_filter' => $charfilter,
                        'filter' => $tokenfilter,
                        'tokenizer' => $tokenizer
                    ]
                    ]
                ]
            ]
        );
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
