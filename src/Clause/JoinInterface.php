<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use QueryBuilder\Expression\ExpressionInterface;

interface JoinInterface extends ExpressionInterface
{
    /**
     * @throws InvalidArgumentException
     * @param string $pk
     */
    public function on(string $pk);

    /**
     * @throws InvalidArgumentException
     * @param string $fk
     */
    public function references(string $fk);

    /**
     * @throws RuntimeException
     * @return string
     */
    public function getExpression();
}
