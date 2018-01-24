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
    /**
     * result field array key
     */
    const RESULT = 'result';

    const SORT_STRING = 'sort-string';
    const SORT_NUMBER = 'sort-numeric';
    const SORT_DATE = 'sort-date';

    const FILTER_STRING = 'filter-string';
    const FILTER_NUMBER = 'filter-numeric';
    const FILTER_DATE = 'filter-date';

    const VISIBILITY = 'visibility';
    const STATUS = 'status';

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
        $this->data = [
            self::RESULT => [],
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
     * set result data
     *
     * @param array $data
     */
    public function addResultData(array $data)
    {
        $this->data[self::RESULT] += $data;
    }

    /**
     * set result data
     *
     * @param array $data
     */
    public function setResultData(array $data)
    {
        $this->data[self::RESULT] = $data;
    }

    public function addSortString($key, $value)
    {
        $this->data[self::SORT_STRING][$key] = $value;
    }

    public function addSortNumeric($key, $value)
    {
        $this->data[self::SORT_NUMBER][$key] = $value;
    }

    public function addSortDate($key, $value)
    {
        $this->data[self::SORT_DATE][$key] = $value;
    }

    /**
     * add string value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterString($name, $value)
    {
        $this->data[self::FILTER_STRING][] = ['name' => $name, 'value' => $value];
    }

    /**
     * add numeric value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterNumeric($name, $value)
    {
        $this->data[self::FILTER_NUMBER][] = ['name' => $name, 'value' => $value];
    }

    /**
     * add numeric value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterDate($name, $value)
    {
        $this->data[self::FILTER_DATE][] = ['name' => $name, 'value' => $value];
    }

    /**
     * set visibility (products)
     *
     * @param int $value
     */
    public function setVisibility(int $value)
    {
        $this->data[self::VISIBILITY] = $value;
    }

    /**
     * set document status
     *
     * @param $value
     */
    public function setStatus(int $value)
    {
        $this->data[self::STATUS] = $value;
    }

    public function setCategories($value)
    {
        $this->data['category']['direct'] = $value;
    }

    public function setAnchors($value)
    {
        $this->data['category']['anchors'] = $value;
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
        $return[] = [
            'index' => [
                '_index' => $index,
                '_type' => $this->docType,
                '_id' => $this->docId,
            ]
        ];
        $return[] = $this->data;
        return $return;
    }
}