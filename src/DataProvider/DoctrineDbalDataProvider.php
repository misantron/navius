<?php

namespace Navius\DataProvider;

use Doctrine\DBAL\Query\QueryBuilder;

class DoctrineDbalDataProvider implements DataProviderInterface
{
    /** @var QueryBuilder */
    private $queryBuilder;
    /** @var callable */
    private $queryBuilderCountModifyCallback;

    public function __construct(QueryBuilder $queryBuilder, callable $queryBuilderCountModifyCallback = null)
    {
        if($queryBuilder->getType() !== QueryBuilder::SELECT){
            throw new \InvalidArgumentException();
        }

        if(!is_null($queryBuilderCountModifyCallback) && !is_callable($queryBuilderCountModifyCallback)){
            throw new \InvalidArgumentException();
        }

        $this->queryBuilder = $queryBuilder;
        $this->queryBuilderCountModifyCallback = $queryBuilderCountModifyCallback;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array|null
     */
    public function getDataSlice($offset, $limit)
    {
        $queryBuilder = clone $this->queryBuilder;
        $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * @return int
     */
    public function getDataRowsCount()
    {
        $queryBuilder = clone $this->queryBuilder;
        if(is_callable($this->queryBuilderCountModifyCallback)){
            call_user_func($this->queryBuilderCountModifyCallback, $queryBuilder);
        } else {
            $queryBuilder
                ->resetQueryPart('select')
                ->select('COUNT(1)')
                ->resetQueryPart('orderBy')
                ->resetQueryPart('groupBy')
            ;
        }
        return (int) $queryBuilder->execute()->fetchColumn();
    }
}