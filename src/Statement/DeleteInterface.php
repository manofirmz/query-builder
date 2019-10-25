<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\StatementInterface;
use QueryBuilder\Expression\ExpressionInterface;

interface DeleteInterface extends StatementInterface
{
    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return DeleteInterface
     */
    public function from(string $entity);

    /**
     * @param ExpressionInterface $filter
     * @return DeleteInterface
     */
    public function where(ExpressionInterface $filter);
}
