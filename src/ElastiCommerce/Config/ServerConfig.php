<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Config;

abstract class ServerConfig
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $authUsername;

    /**
     * @var string
     */
    protected $authPassword;

    /**
     * @param string $host
     * @return ServerConfig
     */
    public function setHost($host): ServerConfig
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param int $port
     * @return ServerConfig
     */
    public function setPort($port): ServerConfig
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @param string $httpBasicAuthUsername
     */
    public function setAuthUsername($authUsername): ServerConfig
    {
        $this->authUsername = $authUsername;
        return $this;
    }

    /**
     * @param string $httpBasicAuthPassword
     */
    public function setAuthPassword($authPassword): ServerConfig
    {
        $this->authPassword = $authPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Return unique resource identifier
     *
     * @return string
     */
    public function getServerInfo(): string
    {
        return sprintf('%s:%s', $this->host, $this->port);
    }

    /**
     * @return boolean
     */
    public function isUseAuth(): bool
    {
        return false === empty($this->authUsername)
            && false === empty($this->authPassword);
    }

    /**
     * @return string
     */
    public function getAuthUsername(): string
    {
        return $this->authUsername;
    }

    /**
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->authPassword;
    }
}