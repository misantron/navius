<?php

namespace Navius;

use Doctrine\DBAL\Query\QueryBuilder;
use Navius\DataProvider\ArrayDataProvider;
use Navius\DataProvider\DataProviderInterface;
use Navius\DataProvider\DoctrineDbalDataProvider;

class PaginationFactory
{
    /**
     * @param array $data
     * @return Pagination
     */
    public function getForArray($data)
    {
        $dataProvider = new ArrayDataProvider($data);
        return $this->create($dataProvider);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param callable|null $countModifyCallback
     * @return Pagination
     */
    public function getForDoctrineDbal($queryBuilder, $countModifyCallback = null)
    {
        $dataProvider = new DoctrineDbalDataProvider($queryBuilder, $countModifyCallback);
        return $this->create($dataProvider);
    }

    /**
     * @param DataProviderInterface $dataProvider
     * @return Pagination
     */
    protected function create($dataProvider)
    {
        return new Pagination($dataProvider);
    }
}