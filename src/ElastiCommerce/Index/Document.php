<?php
declare(strict_types=1);

namespace SmartDevs\ElastiCommerce\Index;

/**
 * This class holds the data of items which can be send as bulk or standalone
 *
 * Class Document
 *
 * @package SmartDevs\ElastiCommerce\Index
 */
class Document extends \SmartDevs\ElastiCommerce\Util\Data\DataObject
{
    const VISIBILITY = 'visibility';
    const STATUS = 'status';

    const SORT_STRING = 'sort-string';
    const SORT_NUMBER = 'sort-numeric';
    const SORT_DATE = 'sort-date';

    const FILTER_STRING = 'filter-string';
    const FILTER_NUMBER = 'filter-numeric';
    const FILTER_DATE = 'filter-date';

    /**
     * document id
     *
     * @var integer
     */
    protected $docId = null;

    /**
     * document type
     *
     * @var string
     */
    protected $docType = null;

    /**
     * document action type for bulk requests
     *
     * @var string
     */
    protected $action = null;

    /**
     * document data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Document constructor
     *
     * @param string $docId
     * @param string $docType
     * @param string $action
     */
    public function __construct(string $docId, string $docType, string $action = 'create')
    {
        $this->docId = $docId;
        $this->docType = $docType;
        $this->action = $action;
        $this->_data = [
            'result' => [],
            self::SORT_STRING => [],
            self::SORT_NUMBER => [],
            self::SORT_DATE => [],
            self::FILTER_STRING => [],
            self::FILTER_NUMBER => [],
            self::FILTER_DATE => []
        ];
    }

    /**
     * @param string $docId
     * @return $this
     */
    public function setId(string $docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * get document id
     *
     * @return string
     */
    public function getId()
    {
        return $this->docId;
    }

    /**
     * set visibility (products)
     *
     * @param int $value
     */
    public function setVisibility(int $value)
    {
        $this->_data[self::VISIBILITY] = $value;
    }

    /**
     * set document status
     *
     * @param $value
     */
    public function setStatus(int $value)
    {
        $this->_data[self::STATUS] = $value;
    }

    public function setCategories($value){
        $this->_data['category']['direct'] = $value;
    }

    public function setAnchors($value){
        $this->_data['category']['anchors'] = $value;
    }

    /**
     * add result data
     *
     * @param array $data
     */
    public function addResultData(array $data)
    {
        $this->_data['result'] += $data;
    }

    /**
     * add value for sorting
     *
     * @param $key
     * @param $value
     */
    public function addSort($key, $value, $type = self::SORT_STRING)
    {
        $this->_data[$type][$key] = $value;
    }

    /**
     * add value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilter($name, $value, $type = self::FILTER_STRING)
    {
        $this->_data[$type][] = ['name' => $name, 'value' => $value];
    }

    /**
     * get document prepared for bulk actions
     *
     * @param string $index
     * @return array
     */
    public function getBulkArray(string $index)
    {
        $return = array();
        $return[] = ['index' => [
            '_index' => $index,
            '_type' => $this->docType,
            '_id' => $this->docId,
        ]
        ];
        $return[] = $this->_data;
        return $return;
    }
}