<?php

namespace Navius\DataProvider;

class ArrayDataProvider implements DataProviderInterface
{
    /** @var array */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array|null
     */
    public function getDataSlice($offset, $limit)
    {
        return array_splice($this->data, $offset, $limit);
    }

    /**
     * @return int
     */
    public function getDataRowsCount()
    {
        return sizeof($this->data);
    }
}