<?php
declare(strict_types=1);

namespace QueryBuilder\Filter;

use QueryBuilder\Operator\Logic;
use PHPUnit\Framework\TestCase;
use \ArrayIterator;
use \InvalidArgumentException;
use \stdClass;

class FilterTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Key should be a string.
     */
    public function testThrowExceptionWhenInvalidKey() 
    {
        $filter1 = new Filter(null, '=', 24);
        $filter1 = new Filter([], '=', 24);
        $filter1 = new Filter(1, '=', 24);
        $filter1 = new Filter(true, '=', 24);
        $filter1 = new Filter(new stdClass(), '=', 24);
        $filter1 = new Filter(function () {}, '=', 24);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Operator should be a string.
     */
    public function testThrowExceptionWhenInvalidOperator() 
    {
        $filter2 = new Filter('age', null, 24);
        $filter2 = new Filter('age', [], 24);
        $filter2 = new Filter('age', 1, 24);
        $filter2 = new Filter('age', true, 24);
        $filter2 = new Filter('age', new stdClass(), 24);
        $filter2 = new Filter('age', function () {}, 24);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value should be a array, string, numeric, bool or null.
     */
    public function testThrowExceptionWhenInvalidValue() 
    {
        $filter = new Filter('age', '=', new stdClass());
        $filter = new Filter('age', '=', function () {});
    }
    
    public function testShouldCreateASimpleFilter()
    {
        $expression1 = '(age >= ?)';
        $params1 = [24];
        $filter1 = new Filter('age', '>=', 24);

        $this->assertEquals($expression1, $filter1->getExpression());
        $this->assertEquals($params1, $filter1->getParams());

        $expression2 = '(id IN (?, ?, ?))';
        $params2 = [1, 2, 3];
        $filter2 = new Filter('id', 'IN', [1, 2, 3]);
        
        $this->assertEquals($expression2, $filter2->getExpression());
        $this->assertEquals($params2, $filter2->getParams());

        $expression3 = '(email IS ?)';
        $params3 = ['NULL'];
        $filter3 = new Filter('email', 'IS', null);
        
        $this->assertEquals($expression3, $filter3->getExpression());
        $this->assertEquals($params3, $filter3->getParams());

        $expression4 = '(status = ?)';
        $params4 = ['TRUE'];
        $filter4 = new Filter('status', '=', true);

        $this->assertEquals($expression4, $filter4->getExpression());
        $this->assertEquals($params4, $filter4->getParams());
    }

    public function testShouldCreateAComplexFilter()
    {
        $expression1 = '((state = ?) AND (age >= ?))';
        $params1 = ['SP', 24];

        $aggregate1 = new FilterAggregate();
        $aggregate1->add(new Filter('state', '=', 'SP'), Logic::AND);
        $aggregate1->add(new Filter('age', '>=', 24));

        $this->assertEquals($expression1, $aggregate1->getExpression());
        $this->assertEquals($params1, $aggregate1->getParams());

        $expression2 = '((state = ?) OR (age <= ?))';
        $params2 = ['RJ', 24];

        $aggregate2 = new FilterAggregate();
        $aggregate2->add(new Filter('state', '=', 'RJ'), Logic::OR);
        $aggregate2->add(new Filter('age', '<=', 24));

        $this->assertEquals($expression2, $aggregate2->getExpression());
        $this->assertEquals($params2, $aggregate2->getParams());

        $expression3 = '(((state = ?) AND (age >= ?)) OR ((state = ?) OR (age <= ?)))';
        $params3 = ['SP', 24, 'RJ', 24];

        $aggregate3 = new FilterAggregate();
        $aggregate3->add($aggregate1, Logic::OR);
        $aggregate3->add($aggregate2);

        $this->assertEquals($expression3, $aggregate3->getExpression());
        $this->assertEquals($params3, $aggregate3->getParams());

        $expression4 = '((gender = ?) AND (state IN (?, ?)))';
        $params4 = ['M', 'SP', 'RJ'];

        $filter4 = new FilterAggregate();
        $filter4->add(new Filter('gender', '=', 'M'), Logic::AND);
        $filter4->add(new Filter('state', 'IN', ['SP', 'RJ']));

        $this->assertEquals($expression4, $filter4->getExpression());
        $this->assertEquals($params4, $filter4->getParams());
    }
}
