<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\SelectInterface;
use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Expression\ParamsInterface;
use QueryBuilder\Clause\GroupByInterface;
use QueryBuilder\Clause\OrderByInterface;
use QueryBuilder\Clause\LimitInterface;
use QueryBuilder\Clause\JoinInterface;
use \InvalidArgumentException;
use \RuntimeException;
use \SplObjectStorage;

class Select implements SelectInterface, ParamsInterface
{
    /**
     * @var string
     */
    private $entity;
    
    /**
     * @var string
     */
    private $alias;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $clauses;

    public function __construct()
    {
        $this->clauses = new SplObjectStorage();
    }
    
    /**
     * @param string $entity
     * @param string $alias = null
     * @throws InvalidArgumentException
     * @return SelectInterface
     */
    public function from(string $entity, string $alias = null)
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity must be a filled string.');
        }

        if (!empty($alias)) {
            $this->alias = $alias;
        }

        $this->entity = $entity;
        return $this;
    }

    /**
     * @param array $columns
     * @throws InvalidArgumentException
     * @return SelectInterface
     */
    public function columns(array $columns)
    {
        $messageOfEmptyColumns  = 'Cannot set value of column with empty string.';
        $messageOfInvalidValues = 'The values of columns must be an string.';

        if (empty($columns)) {
            // todo: test
            throw new InvalidArgumentException($messageOfEmptyColumns);
        }

        foreach ($columns as $column) {
            if (empty($column)) {
                // todo: test
                throw new InvalidArgumentException($messageOfEmptyColumns);
            }

            if (is_array($column)) {
                // todo: test
                throw new InvalidArgumentException($messageOfInvalidValues);
            }
        }

        $this->columns = $columns;
        return $this;
    }

    /**
     * @param ExpressionInterface $filter
     * @return SelectInterface
     */
    public function where(ExpressionInterface $filter)
    {
        $this->add('WHERE', $filter);
        return $this;
    }

    /**
     * @param GroupByInterface $limit
     * @return SelectInterface
     */
    public function groupBy(GroupByInterface $groupBy)
    {
        $this->add('GROUP BY', $groupBy);
        return $this;
    }

    /**
     * @param OrderByInterface $limit
     * @return SelectInterface
     */
    public function orderBy(OrderByInterface $orderBy)
    {
        $this->add('ORDER BY', $orderBy);
        return $this;
    }

    /**
     * @param LimitInterface $limit
     * @return SelectInterface
     */
    public function limit(LimitInterface $limit)
    {
        $this->add('LIMIT', $limit);
        return $this;
    }

    /**
     * @param JoinInterface $join
     */
    public function innerJoin(JoinInterface $join)
    {
        $this->add('INNER JOIN', $join);
        return $this;
    }

    /**
     * @param JoinInterface $join
     */
    public function rightJoin(JoinInterface $join)
    {
        $this->add('RIGHT JOIN', $join);
        return $this;
    }

    /**
     * @param JoinInterface $join
     */
    public function leftJoin(JoinInterface $join)
    {
        $this->add('LEFT JOIN', $join);
        return $this;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getStatement()
    {
        $messageOfUndefinedProperty = '%s is not defined.';
        
        if (empty($this->entity)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Entity'));
        }
        
        $columns   = $this->getColumnsSeparatedByComma();
        $entity    = $this->getEntity();
        $statement = "SELECT {$columns} FROM {$entity} ";
        
        for ($this->clauses->rewind(); $this->clauses->valid(); $this->clauses->next()) {
            $type       = $this->clauses->getInfo();
            $clause     = $this->clauses->current()->getExpression();
            $statement .= "{$type} {$clause}";
        }

        return trim($statement);
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return empty($this->alias) ? $this->entity : "{$this->entity} {$this->alias}";
    }

    /**
     * @return string
     */
    public function getColumnsSeparatedByComma()
    {
        if (empty($this->columns) || $this->hasWildcardInColumns()) {
            return '*';
        }
        
        $alias   = $this->getAlias();
        $columns = array_map(function ($column) use ($alias) {
            return "{$alias}{$column}";
        }, $this->columns);
        
        return implode(', ', $columns);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return empty($this->alias) ? '' : "{$this->alias}.";
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = [];
        for ($this->clauses->rewind(); $this->clauses->valid(); $this->clauses->next()) {
            if ('WHERE' === $this->clauses->getInfo()) {
                foreach ($this->clauses->current()->getParams() as $param) {
                    $params[] = $param;
                }
            }
        }
        return $params;
    }

    /**
     * @param string $type
     * @param ExpressionInterface $clause
     */
    private function add(string $type, ExpressionInterface $clause)
    {
        $this->clauses->attach($clause, $type);
    }

    /**
     * @return bool
     */
    private function hasWildcardInColumns()
    {
        return in_array('*', $this->columns);
    }
}
