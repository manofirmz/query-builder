<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Filter\Filter;
use QueryBuilder\Clause\Limit;
use QueryBuilder\Clause\GroupBy;
use QueryBuilder\Clause\OrderBy;
use QueryBuilder\Clause\Join;
use \InvalidArgumentException;
use \RuntimeException;

class SelectTest extends TestCase
{
    /**
     * @var SelectInterface
     */
    private $statement;

    protected function setUp()
    {
        $this->statement = new Select();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot set value of column with empty string.
     */
    public function testThrowsExceptionIfSetEmptyValueOfColumns()
    {
        $this->statement->columns(['']);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The values of columns must be an string.
     */
    public function testThrowsExceptionIfValueOfColumnsIsInvalid()
    {
        $this->statement->columns([['id']]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity is not defined.
     */
    public function testThrowsExceptionIfEntityIsNotDefined()
    {
        $this->statement->columns(['id', 'name'])->getStatement();
    }

    public function testShouldCreateAnSimpleSelectStatement()
    {
        $statement = 'SELECT id, name, surname, email FROM user';
        $params = [];

        $this->statement
            ->from('user')
            ->columns(['id', 'name', 'surname', 'email']);

        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }

    public function testShouldCreateAnSelectStatementWithWildCard()
    {
        $statement = 'SELECT * FROM user';
        $params = [];

        $this->statement
            ->columns(['id', 'name', 'surname', 'email', '*'])
            ->from('user');

        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }

    public function testShouldCreateAnSelectStatementWithWhereClause()
    {
        $statement = 'SELECT u.id, u.name, u.surname, u.email FROM user u WHERE (id = ?)';
        $params = [1];

        $this->statement
            ->from('user', 'u')
            ->columns(['id', 'name', 'surname', 'email'])
            ->where(new Filter('id', '=', 1));

        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }

    public function testShouldCreateAnSelectStatementWithGroupByClause()
    {
        $statement = 'SELECT name, COUNT(gender) FROM user GROUP BY gender';
        
        $this->statement
             ->columns(['name', 'COUNT(gender)'])
             ->from('user')
             ->groupBy(new GroupBy(['gender']));

        $this->assertEquals($statement, $this->statement->getStatement());
    }

    public function testShouldCreateAnSelectStatementWithLimitClause()
    {
        $statement1 = 'SELECT u.id, u.name, u.surname, u.email FROM user u LIMIT 4, 5';
        $query1 = new Select();
        $query1
            ->from('user', 'u')
            ->columns(['id', 'name', 'surname', 'email'])
            ->limit(new Limit(5, 4));

        $this->assertEquals($statement1, $query1->getStatement());

        $statement2 = 'SELECT u.id, u.name, u.surname, u.email FROM user u LIMIT 4 OFFSET 5';
        $query2 = new Select();
        $query2
            ->from('user', 'u')
            ->columns(['id', 'name', 'surname', 'email'])
            ->limit(new Limit(4, 5));

        $this->assertEquals($statement2, $query2->getStatement());
    }

    public function testShouldCreateAnSelectStatementWithOrderByClause()
    {
        $statement1 = 'SELECT id, name FROM user ORDER BY name';
        $query1 = new Select();
        $query1
            ->columns(['id', 'name'])
            ->from('user')
            ->orderBy(new OrderBy(['name']));

        $this->assertEquals($statement1, $query1->getStatement());

        $statement2 = 'SELECT id, name FROM user ORDER BY id ASC';
        $query2 = new Select();
        $query2
            ->columns(['id', 'name'])
            ->from('user')
            ->orderBy(new OrderBy(['id'], 'ASC'));

        $this->assertEquals($statement2, $query2->getStatement());
    }

    public function testShouldCreateAnSelectStatementWithJoinClause()
    {
        $statement = 'SELECT * FROM x INNER JOIN entity_a a ON a.fk = b.pk RIGHT JOIN entity_b b ON b.fk = c.pk LEFT JOIN entity_c c ON c.fk = d.pk';
        
        $inner  = new Join('entity_a', 'a');
        $inner->on('a.fk')->references('b.pk');

        $right  = new Join('entity_b', 'b');
        $right->on('b.fk')->references('c.pk');

        $left  = new Join('entity_c', 'c');
        $left->on('c.fk')->references('d.pk');

        $this->statement
            ->columns(['*'])
            ->from('x')
            ->innerJoin($inner)
            ->rightJoin($right)
            ->leftJoin($left);

        $this->assertEquals($statement, $this->statement->getStatement());
    }
}
