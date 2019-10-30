<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use PHPUnit\Framework\TestCase;
use \InvalidArgumentException;

class OrderByTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionIfIsInvalidConstant()
    {
        $this->expectExceptionMessage('LALA is not valid constant.');
        $orderBy = new OrderBy(['name', 'id'], 'LALA');
    }

    public function testShouldCreateAnOrderByClause()
    {
        $clause1  = 'id ASC';
        $orderBy1 = new OrderBy(['id'], 'ASC');
        $this->assertEquals($clause1, $orderBy1->getExpression());

        $clause2  = 'name, id DESC';
        $orderBy2 = new OrderBy(['name', 'id'], 'DESC');
        $this->assertEquals($clause2, $orderBy2->getExpression());

        $clause3  = 'name, id';
        $orderBy3 = new OrderBy(['name', 'id']);
        $this->assertEquals($clause3, $orderBy3->getExpression());

        $clause4  = 'name';
        $orderBy4 = new OrderBy(['name']);
        $this->assertEquals($clause4, $orderBy4->getExpression());
    }
}
