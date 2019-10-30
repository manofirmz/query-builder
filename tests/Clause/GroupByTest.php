<?php
declare(strict_types=1);

namespace QueryBuilder\Clause;

use PHPUnit\Framework\TestCase;

class GroupByTest extends TestCase
{
    public function testShouldCreateAnGroupByClause()
    {
        $clause1  = 'a.name';
        $groupBy1 = new GroupBy(['a.name']);

        $this->assertEquals($clause1, $groupBy1->getExpression());

        $clause2  = 'a.name, b.surname';
        $groupBy2 = new GroupBy(['a.name', 'b.surname']);

        $this->assertEquals($clause2, $groupBy2->getExpression());
    }
}
