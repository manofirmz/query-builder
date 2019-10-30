<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use QueryBuilder\Clause\JoinInterface;
use \InvalidArgumentException;
use \RuntimeException;

class Join implements JoinInterface
{
    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $pk;

    /**
     * @var string
     */
    protected $fk;

    /**
     * @param string $entity
     * @param string $alias
     */
    public function __construct(string $entity, string $alias = null)
    {
        if (isset($alias)) {
            $this->alias = $alias;
        }

        $this->entity = $entity;
    }

    /**
     * @throws InvalidArgumentException
     * @param string $pk
     */
    public function on(string $pk)
    {
        if (empty($pk)) {
            throw new InvalidArgumentException('Primary key must be a filled string.');
        }

        $this->pk = $pk;
        return $this;
    }

    /**
     * @throws InvalidArgumentException
     * @param string $fk
     */
    public function references(string $fk)
    {
        if (empty($fk)) {
            throw new InvalidArgumentException('Foreign key must be a filled string.');
        }

        $this->fk = $fk;
        return $this;
    }

    /**
     * @throws RuntimeException
     * @return string
     */
    public function getExpression()
    {   
        $messageOfUndefinedProperty = '%s key is not defined.';

        if (empty($this->pk)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Primary'));
        }

        if (empty($this->fk)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Foreign'));
        }

        $alias  = isset($this->alias) ? $this->alias : '';
        return "{$this->entity} {$alias} ON {$this->pk} = {$this->fk} ";
    }
}
