<?php
declare(strict_types=1);

namespace QueryBuilder\Filter;

use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Expression\ParamsInterface;
use QueryBuilder\Operator\Logic;
use \InvalidArgumentException;

/**
 * Implements an expression filter
 * 
 * @package manofirmz/query-builder
 * @author Rafael Felipe aka Manofirmz
 */
class Filter implements ExpressionInterface, ParamsInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $key
     * @param string $operator
     * @param mixed $value
     */
    public function __construct($key, $operator, $value)
    {
        $this->setKey($key);
        $this->setOperator($operator);
        $this->setValue($value);
    }

    /**
     * @param string
     * @throws InvalidArgumentException
     */
    private function setKey($key)
    {
        if (empty($key) || !is_string($key)) {
            throw new InvalidArgumentException('Key should be a string.');
        }

        $this->key = $key;
    }

    /**
     * @param string
     * @throws InvalidArgumentException
     */
    private function setOperator($operator)
    {
        if (empty($operator) || !is_string($operator)) {
            throw new InvalidArgumentException('Operator should be a string.');
        }

        $this->operator = $operator;
    }

    /**
     * @param mixed
     * @throws InvalidArgumentException
     */
    private function setValue($value)
    {
        if (is_array($value)) {
            $param = array_values($value);
        }

        if (is_string($value)) {
            $param = $value;
        }
        
        if (is_numeric($value)) {
            $param = (int) $value;
        }

        if (is_bool($value)) {
            $param = $value ? 'TRUE' : 'FALSE';
        }

        if (is_null($value)) {
            $param = 'NULL';
        }

        if (empty($param)) {
            $message = 'Value should be a array, string, numeric, bool or null.';
            throw new InvalidArgumentException($message);
        }

        $this->value = $param;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return "({$this->key} {$this->operator} {$this->getParameter()})";
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return is_array($this->value) ? $this->value : [$this->value];
    }

    /**
     * @return string
     */
    private function getParameter()
    {
        if (is_array($this->value)) {
            $parameter = array_map(function ($item) {
                return '?';
            }, $this->value);

            return '(' . implode(', ', $parameter) . ')';
        }

        return '?';
    }
}
