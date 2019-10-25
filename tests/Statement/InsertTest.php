<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use PHPUnit\Framework\TestCase;
use \InvalidArgumentException;
use \LengthException;
use \RuntimeException;

class InsertTest extends TestCase
{
    /**
     * @var InsertInterface
     */
    private $statement;

    protected function setUp()
    {
        $this->statement = new Insert();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entity must be string filled.
     */
    public function testShouldThrowsExceptionIfEmptyEntity()
    {
        $this->statement->into('');
    }

    public function testTheIntoMethodShouldReturnInstanceOfInsertInterface()
    {
        $insert = $this->statement->into('user');
        $this->assertInstanceOf(InsertInterface::class, $insert);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Must be an array filled with columns.
     */
    public function testShouldThrowsExceptionIfEmptyColumns()
    {
        $this->statement->columns([]);
    }

    public function testTheColumnsMethodShouldReturnInstanceOfInsertInterface()
    {
        $insert = $this->statement->columns(['name', 'surname', 'email']);
        $this->assertInstanceOf(InsertInterface::class, $insert);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Must be an array filled with values.
     */
    public function testShouldThrowsExceptionIfEmptyValues()
    {
        $this->statement->values([]);
    }

    public function testTheValuesMethodShouldReturnInstanceOfInsertInterface()
    {
        $insert = $this->statement->values(['Rafael', 'Felipe', 'manofirmz@gmail.com']);
        $this->assertInstanceOf(InsertInterface::class, $insert);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity must be defined.
     */
    public function testThrowsExceptionIfEntityIsNotDefined()
    {
        $this->statement
             ->columns(['name', 'surname', 'email'])
             ->values(['Rafael', 'Felipe', 'manofirmz@gmail.com'])
             ->getStatement();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Columns must be defined.
     */
    public function testThrowsExceptionIfColumnsIsNotDefined()
    {
        $this->statement
             ->into('user')
             ->values(['Rafael', 'Felipe', 'manofirmz@gmail.com'])
             ->getStatement();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Values must be defined.
     */
    public function testThrowsExceptionIfValuesIsNotDefined()
    {
        $this->statement
             ->into('user')
             ->columns(['name', 'surname', 'email'])
             ->getStatement();
    }

    /**
     * @expectedException LengthException
     * @expectedExceptionMessage The size of columns does not match with size of values.
     */
    public function testThrowsExceptionIfSizeOfColumnsMoreThanSizeOfValues()
    {
        $this->statement
             ->into('user')
             ->columns(['name', 'surname', 'email'])
             ->values(['Rafael', 'Felipe'])
             ->getStatement();
    }

    /**
     * @expectedException LengthException
     * @expectedExceptionMessage The size of values does not match with size of columns.
     */
    public function testThrowsExceptionIfSizeOfValuesMoreThanSizeOfColumns()
    {
        $this->statement
             ->into('user')
             ->columns(['name', 'surname'])
             ->values(['Rafael', 'Felipe', 'manofirmz@gmail.com'])
             ->getStatement();
    }

    public function testShouldCreateAnInsertStatement()
    {
        $statement = 'INSERT INTO user (name, surname, email) VALUES (?, ?, ?)';
        $params    = ['Rafael', 'Felipe', 'manofirmz@gmail.com'];
        
        $this->statement
             ->into('user')
             ->columns(['name', 'surname', 'email'])
             ->values(['Rafael', 'Felipe', 'manofirmz@gmail.com']);

        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }
}
