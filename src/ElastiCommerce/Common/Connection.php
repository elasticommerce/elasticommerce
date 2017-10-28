<?php
/**
 * Created by PhpStorm.
 * User: dng
 * Date: 06.02.17
 * Time: 22:32
 */

namespace SmartDevs\ElastiCommerce\Common;

use SmartDevs\ElastiCommerce\Implementor\Config;

final class Connection
{

    /**
     * @var Config
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
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * gets the dsn for an connection
     */
    protected function getConnectDsn()
    {
        if (true === $this->config->getServerConfig()->isUseAuth()) {
            return sprintf('http://%s:%s@%s:%s',
                $this->config->getServerConfig()->getHttpBasicAuthUsername(),
                $this->config->getServerConfig()->getHttpBasicAuthPassword(),
                $this->config->getServerConfig()->getHost(),
                $this->config->getServerConfig()->getPort());
        } else {
            return sprintf('http://%s:%s',
                $this->config->getServerConfig()->getHost(),
                $this->config->getServerConfig()->getPort());
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