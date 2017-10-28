<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Implementor;

use SmartDevs\ElastiCommerce\Config\{
    ServerConfig, IndexConfig
};

/**
 * Interface for configuration reader
 */
interface Config
{

    /**
     * Returns ElastiCommerce server configuration
     *
     * @return ServerConfig
     */
    public function getServerConfig(): ServerConfig;


    /**
     * Return ElastiCommerce index configuration
     *
     * @return IndexConfig
     */
    public function getIndexConfig(): IndexConfig;
}