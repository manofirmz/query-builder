<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use QueryBuilder\Statement\DeleteInterface;
use QueryBuilder\Expression\ExpressionInterface;
use QueryBuilder\Expression\ParamsInterface;
use \InvalidArgumentException;
use \RuntimeException;

class Delete implements DeleteInterface, ParamsInterface
{
    /**
     * @var string
     */
    private $entity;

    /**
     * @var ExpressionInterface
     */
    private $filter;

    /**
     * @param string $entity
     * @throws InvalidArgumentException
     * @return DeleteInterface
     */
    public function from(string $entity)
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity must be a filled string.');
        }

        $this->entity = $entity;
        return $this;
    }

    /**
     * @param ExpressionInterface $expression
     * @return DeleteInterface
     */
    public function where(ExpressionInterface $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getStatement()
    {
        $messageOfUndefinedProperty = '%s is not defined.';
        $statement                  = 'DELETE FROM %s WHERE %s';

        if (empty($this->entity)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Entity'));
        }

        if (empty($this->filter)) {
            throw new RuntimeException(sprintf($messageOfUndefinedProperty, 'Where clause'));
        }

        return sprintf(
            $statement, 
            $this->getEntity(), 
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
    public function getParams()
    {
        return $this->filter->getParams();
    }
}
