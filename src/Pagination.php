<?php

namespace Navius;

use Navius\DataProvider\DataProviderInterface;

class Pagination implements \Countable, \IteratorAggregate
{
    /** @var DataProviderInterface */
    private $dataProvider;

    /** @var int */
    private $currentPage;
    /** @var int */
    private $pageSize;
    /** @var array */
    private $currentPageData;
    /** @var int */
    private $dataRowsCount;

    /** @var bool */
    private $normalizeOutOfRangePages;

    /**
     * @param DataProviderInterface $dataProvider
     */
    public function __construct($dataProvider)
    {
        $this->dataProvider = $dataProvider;

        $this->currentPage = 1;
        $this->pageSize = 10;

        $this->normalizeOutOfRangePages = false;
    }

    /**
     * @return DataProviderInterface
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setNormalizeOutOfRangePages($value)
    {
        $this->normalizeOutOfRangePages = (bool)$value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getNormalizeOutOfRangePages()
    {
        return $this->normalizeOutOfRangePages;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setCurrentPage($page)
    {
        $currentPage = (int) $page;
        if($currentPage < 1){
            throw new \InvalidArgumentException();
        }
        if($currentPage > $this->getPageCount()){
            if($this->normalizeOutOfRangePages){
                $currentPage = 1;
            } else {
                throw new \OutOfRangeException();
            }
        }
        $this->currentPage = $currentPage;

        $this->currentPageData = null;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setPageSize($size)
    {
        $pageSize = (int) $size;
        if($pageSize < 1){
            throw new \InvalidArgumentException();
        }
        $this->pageSize = $pageSize;

        $this->currentPageData = null;
        $this->dataRowsCount = null;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getPageCount()
    {
        return (int) ceil($this->getDataRowCount() / $this->pageSize);
    }

    /**
     * @return array|null
     */
    public function getCurrentPageData()
    {
        if($this->currentPageData === null){
            $limit = $this->pageSize;
            $offset = ($this->currentPage - 1) * $limit;
            $this->currentPageData = $this->dataProvider->getDataSlice($offset, $limit);
        }
        return $this->currentPageData;
    }

    /**
     * @return int
     */
    public function getDataRowCount()
    {
        if($this->dataRowsCount === null){
            $this->dataRowsCount = $this->dataProvider->getDataRowsCount();
        }
        return $this->dataRowsCount;
    }

    /**
     * @return bool
     */
    public function hasPreviousPage()
    {
        return $this->currentPage > 1;
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        if($this->hasPreviousPage()){
            return $this->currentPage - 1;
        }
        throw new \LogicException();
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->currentPage < $this->getPageCount();
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        if($this->hasNextPage()){
            return $this->currentPage + 1;
        }
        throw new \LogicException();
    }

    /**
     * @return array|\ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        $data = $this->getCurrentPageData();

        if($data instanceof \Iterator){
            return $data;
        }
        if($data instanceof \IteratorAggregate){
            return $data->getIterator();
        }
        return new \ArrayIterator($data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getDataRowCount();
    }
}