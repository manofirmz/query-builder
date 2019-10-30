<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\StatementInterface;
use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Clause\GroupByInterface;
use QueryBuilder\Clause\OrderByInterface;
use QueryBuilder\Clause\LimitInterface;

interface SelectInterface extends StatementInterface
{
    /**
     * @param string $entity
     * @param string $alias = null
     * @throws InvalidArgumentException
     * @return SelectInterface
     */
    public function from(string $entity, string $alias = null);

    /**
     * @param array $columns
     * @throws InvalidArgumentException
     * @return SelectInterface
     */
    public function columns(array $columns);

    /**
     * @param ExpressionInterface $filter
     * @return SelectInterface
     */
    public function where(ExpressionInterface $filter);

    /**
     * @param GroupByInterface $limit
     * @return SelectInterface
     */
    public function groupBy(GroupByInterface $groupBy);

    /**
     * @param OrderByInterface $limit
     * @return SelectInterface
     */
    public function orderBy(OrderByInterface $orderBy);


    /**
     * @param LimitInterface $limit
     * @return SelectInterface
     */
    public function limit(LimitInterface $limit);
}
