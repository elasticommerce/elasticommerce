<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Implementor\Facades;

/**
 * Interface for index facade
 */
interface IndexFacadeImplementor
{
    public function exists($indexName): bool;

    public function create(string $indexName, int $numberOfShards, int $numberOfReplicas): bool;

    public function delete(array $indexNames): bool;

    public function update(): bool;

    public function refresh(array $indexNames): bool;

    public function existsAlias(string $aliasName): bool;

    public function hasAlias(string $indexName, string $indexAlias): bool;

    public function createAlias(string $indexName, string $aliasName): bool;

    public function deleteAliasByIndex(string $aliasName, string $indexName): bool;

    public function deleteAlias(string $aliasName): bool;

    public function rotateAlias(string $indexName, string $aliasName): bool;

}