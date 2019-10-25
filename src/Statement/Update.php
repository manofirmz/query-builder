<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\UpdateInterface;
use QueryBuilder\Expression\ExpressionInterface;
use \InvalidArgumentException;
use \RuntimeException;

class Update implements UpdateInterface
{
    /**
     * @var string
     */
    private $entity;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var ExpressionInterface
     */
    private $filter;

    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return UpdateInterface
     */
    public function table(string $entity) 
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity must be filled string.');
        }
        
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param array $columns ['key' => 'values']
     * @throws InvalidArgumentException
     * @return UpdateInterface
     */
    public function set(array $columns) 
    {
        if (empty($columns)) {
            throw new InvalidArgumentException('Columns must be filled array.');
        }

        $this->columns = array_map(function ($value) {
            if (is_array($value)) {
                $message = 'Value of column must be string, number or bool.';
                throw new InvalidArgumentException($message);
            }
        
            if (is_string($value) || is_numeric($value)) {
                return $value;
            }
        
            if (is_bool($value)) {
                return $value ? 'TRUE' : 'FALSE';
            }
        
            if (is_null($value)) {
                return 'NULL';
            }    
        }, $columns);
        return $this;
    }

    /**
     * @param ExpressionInterface $filter
     * @return UpdateInterface
     */
    public function where(ExpressionInterface $filter) 
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatement() 
    {
        $messageOfUndefinedProperty = '%s is not defined.';
        $statement                  = 'UPDATE %s SET %s WHERE %s';
        
        if (empty($this->entity)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Entity'));
        }

        if (empty($this->columns)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Columns'));
        }

        if (empty($this->filter)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Where clause'));
        }

        return sprintf(
            $statement, 
            $this->getEntity(), 
            $this->getPlaceholders(), 
            $this->filter->getExpression()
        );
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getParams() 
    {
        $paramsOfColumns = array_values($this->columns);
        $paramsOfFilter  = $this->filter->getParams();
        return array_merge($paramsOfColumns, $paramsOfFilter);
    }

    /**
     * @return string
     */
    private function getPlaceholders()
    {
        $columns = array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($this->columns));
        return implode(', ', $columns);
    }
}
