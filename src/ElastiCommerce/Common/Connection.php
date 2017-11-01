<?php
/**
 * Created by PhpStorm.
 * User: dng
 * Date: 06.02.17
 * Time: 22:32
 */

namespace SmartDevs\ElastiCommerce\Common;

use SmartDevs\ElastiCommerce\Config\ServerConfig;

final class Connection
{

    /**
     * @var ServerConfig
     */
    protected $config = null;

    /**
     * @var
     */
    protected $connection = null;

    /**
     * Client Connection constructor.
     * @param Config $config
     */
    public function __construct(ServerConfig $serverConfig)
    {
        $this->config = $serverConfig;
    }

    /**
     * gets the dsn for an connection
     */
    protected function getConnectDsn()
    {
        if (true === $this->config->isUseAuth()) {
            return sprintf('http://%s:%s@%s:%s',
                $this->config->getHttpBasicAuthUsername(),
                $this->config->getHttpBasicAuthPassword(),
                $this->config->getHost(),
                $this->config->getPort());
        } else {
            return sprintf('http://%s:%s',
                $this->config->getHost(),
                $this->config->getPort());
        }
    }

    /**
     * get connection
     *
     * @return \Elasticsearch\Client
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = \Elasticsearch\ClientBuilder::create()
                ->setHosts([$this->getConnectDsn()])
                ->setRetries(10)
                ->build();
        }
        return $this->connection;
    }

}