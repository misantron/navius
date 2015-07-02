<?php

namespace Navius\Adapter;

interface DataAdapterInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @return array|null
     */
    public function getDataSlice($offset, $limit);

    /**
     * @return int
     */
    public function getDataRowsCount();
}