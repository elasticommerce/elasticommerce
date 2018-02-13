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

    const SORT_STRING = 'sort_string';
    const SORT_NUMBER = 'sort_numeric';
    const SORT_DATE = 'sort_date';

    const FILTER_STRING = 'filter_string';
    const FILTER_NUMBER = 'filter_numeric';
    const FILTER_DATE = 'filter_date';
    const FILTER_PRICE = 'filter_price';

    const CATEGORY_DIRECT = 'category_direct';
    const CATEGORY_ANCHORS = 'category_anchors';

    const VARIANTS = 'variants';

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
        $this->_data = [
            self::RESULT => [],
            self::SORT_STRING => [],
            self::SORT_NUMBER => [],
            self::SORT_DATE => [],
            self::FILTER_STRING => [],
            self::FILTER_NUMBER => [],
            self::FILTER_DATE => [],
            self::CATEGORY_DIRECT => [],
            self::CATEGORY_ANCHORS => [],
            self::VARIANTS => []
        ];
    }

    /**
     * @param string $docId
     * @return $this
     */
    public function setId($docId)
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
     * add result data
     *
     * @param array $data
     */
    public function addResultData(array $data)
    {
        $this->_data[self::RESULT] += $data;
    }

    /**
     * set result data
     *
     * @param array $data
     */
    public function setResultData(array $data)
    {
        $this->_data[self::RESULT] = $data;
    }

    /**
     * adds string sort data
     *
     * @param $key
     * @param $value
     */
    public function addSortString($key, $value)
    {
        $this->_data[self::SORT_STRING][$key] = $value;
    }

    /**
     * add numeric sort data
     *
     * @param $key
     * @param $value
     */
    public function addSortNumeric($key, $value)
    {
        $this->_data[self::SORT_NUMBER][$key] = $value;
    }

    /**
     * add date sort data
     *
     * @param $key
     * @param $value
     *
     */
    public function addSortDate($key, $value)
    {
        $this->_data[self::SORT_DATE][$key] = $value;
    }

    /**
     * add string value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterString($name, $value)
    {
        $value = is_array($value) ? $value : [$value];
        if (true === array_key_exists($name, $this->_data[self::FILTER_STRING])) {
            $data = ['name' => $name, 'value' => array_unique(array_merge($this->_data[self::FILTER_STRING][$name]['value'], $value))];
        } else {
            $data = ['name' => $name, 'value' => $value];
        }
        $this->_data[self::FILTER_STRING][$name] = $data;
    }

    /**
     * add numeric value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterNumeric($name, $value)
    {
        $value = is_array($value) ? $value : [$value];
        if (true === array_key_exists($name, $this->_data[self::FILTER_NUMBER])) {
            $data = ['name' => $name, 'value' => array_unique(array_merge($this->_data[self::FILTER_NUMBER][$name]['value'], $value))];
        } else {
            $data = ['name' => $name, 'value' => $value];
        }
        $this->_data[self::FILTER_NUMBER][$name] = $data;
    }

    /**
     * add price value for filtering based on customer group
     *
     * @param $key
     * @param $value
     */
    public function addPrice($name, $value)
    {
        $this->_data['price'][$name] = $value;
    }

    public function addVariant(array $variant)
    {
        $this->_data[self::VARIANTS][] = $variant;
    }

    /**
     * add numeric value for filtering
     *
     * @param $key
     * @param $value
     */
    public function addFilterDate($name, $value)
    {
        $this->_data[self::FILTER_DATE][] = ['name' => $name, 'value' => $value];
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

    /**
     * set product categories
     *
     * @param array $value
     */
    public function setCategories(array $value)
    {
        $this->_data[self::CATEGORY_DIRECT] = array_unique(array_merge($this->_data[self::CATEGORY_DIRECT], array_map('intval', $value)));
    }

    /**
     * set product subcategories
     *
     * @param $value
     */
    public function setAnchors(array $value)
    {
        $this->_data[self::CATEGORY_ANCHORS] = array_unique(array_merge($this->_data[self::CATEGORY_ANCHORS], array_map('intval', $value)));
    }

    public function setStock(bool $status, float $qty)
    {
        $this->_data['stock'] = ['status' => (bool)$status, 'qty' => $qty];
    }

    public function setPriceForCustomerGroup($id, array $data)
    {
        $this->_data['price_customer_group_' . $id] = array_map('floatval', $data);
    }

    /**
     * get document prepared for bulk actions
     *
     * @param string $index
     * @return array
     */
    public function getBulkArray(string $index)
    {
        $return = [];
        $return[] = [
            'index' => [
                '_index' => $index,
                '_type' => $this->docType,
                '_id' => $this->docId,
            ]
        ];
        $return[] = $this->_data;
        $return[1][self::FILTER_NUMBER] = array_values($this->_data[self::FILTER_NUMBER]);
        $return[1][self::FILTER_STRING] = array_values($this->_data[self::FILTER_STRING]);
        return $return;
    }
}