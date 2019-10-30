<?php
declare(strict_types=1);

namespace QueryBuilder\Filter;

use QueryBuilder\Expression\ExpressionAggregateInterface;
use QueryBuilder\Expression\ParamsInterface;
use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Operator\Logic;
use \SplObjectStorage;

/**
 * Implements an recursive expression filter
 * 
 * @package manofirmz/query-builder
 * @author Rafael Felipe aka Manofirmz
 */
class FilterAggregate implements ExpressionAggregateInterface, ParamsInterface
{
    /**
     * @var SplObjectStorage
     */
    private $aggregates;

    public function __construct()
    {
        $this->aggregates = new SplObjectStorage();
    }

    /**
     * @param ExpressionInterface $expression
     * @param string $operator
     */
    public function add(ExpressionInterface $expression, $operator = Logic::AND)
    {
        $this->aggregates->attach($expression, $operator);
    }

    /**
     * @return string
     */
    public function getExpression()
    {   
        $expression = '';
        for ($this->aggregates->rewind(); $this->aggregates->valid(); $this->aggregates->next()) {
            $operator    = $this->aggregates->getInfo();
            $current     = $this->aggregates->current();
            $expression .= "{$current->getExpression()} {$operator} ";
        }
        return '(' . preg_replace('/ (AND|OR) $/', '', $expression) . ')';
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = [];
        foreach ($this->aggregates as $aggregate) {
            foreach ($aggregate->getParams() as $param) {
                array_push($params, $param);
            }
        }
        return $params;
    }
}
