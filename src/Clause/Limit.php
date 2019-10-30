<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use QueryBuilder\Clause\LimitInterface;

class Limit implements LimitInterface
{
    /**
     * @var int
     */
    private $limit;
    
    /**
     * @var int
     */
    private $offset;
    
    /**
     * @param int $limit
     * @param int $offset
     */
    public function __construct(int $limit, int $offset = 0)
    {
        $this->limit  = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->offsetIsSmallerThanLimit() 
            ? "{$this->offset}, {$this->limit}" 
            : "{$this->limit} OFFSET {$this->offset}";
    }

    /**
     * @return bool
     */
    private function offsetIsSmallerThanLimit() {
        return 0 !== $this->offset && $this->offset < $this->limit;
    }
}
