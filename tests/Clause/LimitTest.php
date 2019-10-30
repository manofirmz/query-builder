<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use PHPUnit\Framework\TestCase;

class LimitTest extends TestCase
{
    public function testShouldCreateACommonLimitClause()
    {
        $clause1 = '3 OFFSET 0';
        $limit1  = new Limit(3);

        $this->assertEquals($clause1, $limit1->getExpression());
        
        $clause2 = '3 OFFSET 5';
        $limit2  = new Limit(3, 5);

        $this->assertEquals($clause2, $limit2->getExpression());
    }

    public function testShouldCreateShortLimitClause()
    {
        $clause = '3, 5';
        $limit  = new Limit(5, 3);

        $this->assertEquals($clause, $limit->getExpression());
    }
}
