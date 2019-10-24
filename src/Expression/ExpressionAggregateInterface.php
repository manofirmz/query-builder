<?php
declare(strict_types=1);

namespace QueryBuilder\Expression;

use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Operator\Logic;

/**
 * @package manofirmz/query-builder
 * @author Rafael Felipe aka Manofirmz
 */
interface ExpressionAggregateInterface extends ExpressionInterface
{
    /**
     * @param ExpressionInterface $expression
     * @param string $operator
     */
    public function add(ExpressionInterface $expression, $operator = Logic::AND);
}
