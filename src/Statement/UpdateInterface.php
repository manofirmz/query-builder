<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\StatementInterface;
use QueryBuilder\Expression\ExpressionInterface;

interface UpdateInterface extends StatementInterface
{
    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return UpdateInterface
     */
    public function table(string $entity);

    /**
     * @param array $columns ['key' => 'values']
     * @throws InvalidArgumentException
     * @return UpdateInterface
     */
    public function set(array $columns);

    /**
     * @param ExpressionInterface $filter
     * @return UpdateInterface
     */
    public function where(ExpressionInterface $filter);
}
