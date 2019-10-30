<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use QueryBuilder\Clause\GroupByInterface;

class GroupBy implements GroupByInterface
{
    /**
     * @var array
     */
    private $columns;

    /**
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function getExpression() 
    {
        return implode(', ', array_values($this->columns));
    }
}
