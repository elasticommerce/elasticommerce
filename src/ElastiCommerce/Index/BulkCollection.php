<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index;

use SmartDevs\ElastiCommerce\Config\IndexConfig;
use SmartDevs\ElastiCommerce\Util\Data\DataCollection;

class BulkCollection extends DataCollection
{
    /**
     * @var IndexConfig
     */
    protected $indexConfig = null;

    public function __construct(IndexConfig $indexConfig)
    {
        $this->setItemObjectClass('\SmartDevs\ElastiCommerce\Index\Document');
        $this->indexConfig = $indexConfig;
    }

}