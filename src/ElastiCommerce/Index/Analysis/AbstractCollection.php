<?php
declare(strict_types = 1);

namespace SmartDevs\ElastiCommerce\Index\Analysis;

abstract class AbstractCollection extends \SmartDevs\Util\Data\DataObjectCollection
{

    /**
     * @var string[]
     */
    protected $classMapping = [];

    /**
     * get class name from mapping
     *
     * @param string $name
     * @return string
     */
    protected function getClassNamefromMapping(string $name): string
    {
        return $this->classMapping[$name];
    }

    /**
     * AbstractCollection constructor.
     */
    public function __construct()
    {
    }

    /**
     * init collection from xml config
     *
     * @param \SimpleXMLElement $element
     * @return AbstractCollection
     */
    public function setXmlConfig(\SimpleXMLElement $element)
    {
        $node = $element->{static::NODE_NAME};
        foreach ($node->children() as $name => $data) {
            $typeClass = $this->getClassNamefromMapping((string)$data['type']);
            $typeInstance = new $typeClass();
            $typeInstance->setName($name)
                ->setXmlConfig($data);
            $this->setItem($typeInstance);
        }
        return $this;
    }
}