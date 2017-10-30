<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Implementor\Facades;

/**
 * Interface for index facade
 */
interface IndexFacadeImplementor
{

    /**
     * checks an index with the given name exists
     *
     * @param string $indexName
     * @return bool
     */
    public function exists($indexName): bool;

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
    public function create(string $indexName, int $numberOfShards, int $numberOfReplicas): bool;

    /**
     * delete an index
     *
     * @param array $indexNames
     * @return bool
     */
    public function delete(array $indexNames): bool;

    public function update(): bool;

    /**
     * refresh and index
     *
     * @param $indexNames
     * @return bool
     */
    public function refresh(array $indexNames): bool;

    /**
     * checks an alias exists
     *
     * @param string $aliasName
     * @return bool
     */
    public function existsAlias(string $aliasName): bool;

    /**
     * checks an index has an specific alias
     *
     * @param string $indexName
     * @param string $indexAlias
     * @return bool
     */
    public function hasAlias(string $indexName, string $indexAlias): bool;

    /**
     * add an alias for an index
     *
     * @param string $indexName index name
     * @param string $aliasName alias name
     * @return bool
     */
    public function createAlias(string $indexName, string $aliasName): bool;

    /**
     * deletes an alias by an given index name
     *
     * @param string $aliasName
     * @return bool
     */
    public function deleteAliasByIndex(string $aliasName, string $indexName): bool;

    /**
     * deletes an alias
     *
     * @param string $aliasName
     * @return bool
     */
    public function deleteAlias(string $aliasName): bool;

    /**
     * rotate an index alias to an new endpoint
     *
     * @param string $indexName
     * @param string $aliasName
     * @return bool
     */
    public function rotateAlias(string $indexName, string $aliasName): bool;

    /**
     * get an array of orphaned indexes which don't has an alias
     *
     * @param string $prefix
     * @return array
     */
    public function getOrphanedIndices(string $prefix): array;

    /**
     * deletes indexes with given prefix which don't has an alias
     * @param string $prefix
     * @return bool
     */
    public function deleteOrphanedIndices(string $prefix): bool;
}