<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use PHPUnit\Framework\TestCase;
use \InvalidArgumentException;
use \RuntimeException;

class JoinTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Primary key must be a filled string.
     */
    public function testThrowsExceptionIfPkIsEmpty() 
    {
        $join = new Join('address', 'a');
        $join->on('');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Foreign key must be a filled string.
     */
    public function testThrowsExceptionIfFkIsEmpty() 
    {
        $join = new Join('address', 'a');
        $join->references('');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Primary key is not defined.
     */
    public function testThrowsExceptionIfPkIsNotDefined() 
    {
        $join = new Join('address', 'a');
        $join->references('a.pk_city')->getExpression();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Foreign key is not defined.
     */
    public function testThrowsExceptionIfFkIsNotDefined() 
    {
        $join = new Join('address', 'a');
        $join->on('u.fk_city')->getExpression();
    }
    
    public function testShoudCreateAnJoinClause()
    {
        $clause1   = 'address a ON u.fk_city = a.pk_city ';
        $innerjoin = new Join('address', 'a');
        $innerjoin->on('u.fk_city')->references('a.pk_city');

        $this->assertEquals($clause1, $innerjoin->getExpression());
    }
}
