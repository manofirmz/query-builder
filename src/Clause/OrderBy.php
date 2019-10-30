<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use QueryBuilder\Clause\OrderByInterface;
use \InvalidArgumentException;

class OrderBy implements OrderByInterface
{
    /**
     * @var string
     */
    const DESC = 'DESC';
    
    /**
     * @var string
     */
    const ASC  = 'ASC';
    
    /**
     * @var array
     */
    private $orders = [];

    /**
     * @param array $columns
     * @param string $sort
     */
    public function __construct(array $columns, $sort = null)
    {
        if (isset($sort) && !$this->isValidConstant($sort)) {
            $message = '%s is not valid constant.';
            throw new InvalidArgumentException(sprintf($message, $sort));
        }
        
        $this->setOrders($columns, $sort);
    }

    private function setOrders(array $columns, $sort = null)
    {
        $message = 'The value of column %s.';
        $order   = [];

        if (in_array('', $columns)) {
            throw new InvalidArgumentException($messageOfEmptyColumn);
        }

        foreach ($columns as $column) {
            if (empty($column)) {
                throw new InvalidArgumentException(
                    sprintf($messageOfEmptyColumn, 'cannot be empty')
                );
            }

            if (is_array($column)) {
                throw new InvalidArgumentException(
                    sprintf($messageOfEmptyColumn, 'must be a filled string')
                );
            }

            array_push($order, $column);
        }

        if (isset($sort)) {
            array_push($order, $sort);
        }

        array_push($this->orders, $order);
    }

    /**
     * @param string $sort
     * @return bool
     */
    private function isValidConstant(string $sort)
    {
        $class = new \ReflectionClass(__CLASS__);
        return in_array($sort, $class->getConstants());
    }

    public function getExpression()
    {
        $orders = [];
        foreach ($this->orders as $order) {
            $lastIndex = count($order) - 1;
            
            if ($order[$lastIndex] === OrderBy::ASC || 
                $order[$lastIndex] === OrderBy::DESC) {
                $sort     = array_pop($order);
                $orders[] = implode(', ', $order) . " {$sort}";
                continue;
            }

            $orders[] = implode(', ', $order);
        }
        return implode(', ', $orders);
    }
}
