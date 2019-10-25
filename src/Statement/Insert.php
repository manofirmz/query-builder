<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\InsertInterface;
use \InvalidArgumentException;
use \LengthException;
use \RuntimeException;

/**
 * Implements an insert statement with fluent interface
 * 
 * @package manofirmz/query-builder
 * @author Rafael Felipe aka Manofirmz
 */
class Insert implements InsertInterface
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
     * @var array
     */
    private $params;

    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function into(string $entity)
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity must be string filled.');
        }

        $this->entity = $entity;
        return $this;
    }

    /**
     * @param array $columns
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function columns(array $columns)
    {
        if (empty($columns)) {
            throw new InvalidArgumentException('Must be an array filled with columns.');
        }

        $this->columns = $columns;
        return $this;
    }

    /**
     * @param array $values
     * @throws InvalidArgumentException
     * @return InsertInterface
     */
    public function values(array $values)
    {
        if (empty($values)) {
            throw new InvalidArgumentException('Must be an array filled with values.');
        }

        $this->params = array_values($values);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        $messageOfUndefinedProperties = '%s must be defined.';
        $messageOfSizeDoesNotMatch    = 'The size of %s does not match with size of %s.';
        $statement                    = 'INSERT INTO %s (%s) VALUES (%s)';

        if (empty($this->entity)) {
            throw new RuntimeException(
                sprintf($messageOfUndefinedProperties, 'Entity')
            );
        }

        if (empty($this->columns)) {
            throw new RuntimeException(
                sprintf($messageOfUndefinedProperties, 'Columns')
            );
        }

        if (empty($this->params)) {
            throw new RuntimeException(
                sprintf($messageOfUndefinedProperties, 'Values')
            );
        }

        if ($this->getSizeOfColumns() > $this->getSizeOfValues()) {
            throw new LengthException(
                sprintf($messageOfSizeDoesNotMatch, 'columns', 'values')
            );
        }

        if ($this->getSizeOfValues() > $this->getSizeOfColumns()) {
            throw new LengthException(
                sprintf($messageOfSizeDoesNotMatch, 'values', 'columns')
            );
        }

        return sprintf(
            $statement, 
            $this->getEntity(), 
            $this->getColumnsSeparatedByComma(), 
            $this->getPlaceholders()
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
        return $this->params;
    }

    /**
     * @return int
     */
    private function getSizeOfColumns()
    {
        return count($this->columns);
    }
    
    /**
     * @return int
     */
    private function getSizeOfValues()
    {
        return count($this->params);
    }

    /**
     * @return string
     */
    private function getColumnsSeparatedByComma()
    {
        return implode(', ', $this->columns);
    }

    /**
     * @return array
     */
    private function getPlaceholders()
    {
        $parameters = array_map(function () {
            return '?';
        }, $this->params);

        return implode(', ', $parameters);
    }
}
