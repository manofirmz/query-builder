<?php
declare(strict_types=1);

namespace QueryBuilder\Expression;

/**
 * @package manofirmz/query-builder
 * @author Rafael Felipe aka Manofirmz
 */
interface ExpressionInterface
{
    /**
     * @return string
     */
    public function getExpression();
}
