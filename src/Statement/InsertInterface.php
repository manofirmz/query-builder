<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\StatementInterface;

interface InsertInterface extends StatementInterface
{
    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function into(string $entity);

    /**
     * @param array $columns
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function columns(array $columns);

    /**
     * @param array $values
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function values(array $values);
}
