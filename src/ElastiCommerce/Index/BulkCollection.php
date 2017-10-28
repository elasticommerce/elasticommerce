<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Implementor\Config;
use SmartDevs\ElastiCommerce\Util\Data\DataCollection;

class BulkCollection extends DataCollection
{
    /**
     * @var Config
     */
    protected $config = null;

    public function __construct(Config $config)
    {
        $this->setItemObjectClass('\SmartDevs\ElastiCommerce\Index\Document');
        $this->config = $config;
    }

}