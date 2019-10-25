<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Filter\Filter;
use \InvalidArgumentException;
use \RuntimeException;

class UpdateTest extends TestCase
{
    /**
     * @var UpdateInterface
     */
    private $statement;

    protected function setUp()
    {
        $this->statement = new Update();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entity must be filled string.
     */
    public function testThrowsExceptionIfEntityIsEmpty()
    {
        $this->statement->table('');
    }

    public function testTheTableMethodShouldReturnInstanceOfUpdateInterface()
    {
        $update = $this->statement->table('user');
        $this->assertInstanceOf(UpdateInterface::class, $update);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Columns must be filled array.
     */
    public function testThrowsExceptionIfColumnsIsEmpty()
    {
        $this->statement->set([]);
    }

    public function testTheSetMethodShouldReturnInstanceOfUpdateInterface()
    {
        $update = $this->statement->set(['name' => 'Rafael']);
        $this->assertInstanceOf(UpdateInterface::class, $update);
    }
    
    public function testTheWhereMethodShouldReturnInstanceOfUpdateInterface()
    {
        $update = $this->statement->where(new Filter('id', '=', 1));
        $this->assertInstanceOf(UpdateInterface::class, $update);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Value of column must be string, number or bool.
     */
    public function testThrowsExceptionIfValuesOfColumnsIsAnArray()
    {
        $this->statement->set([
            'name' => 'Rafael', 
            'email' => ['manofirmz@gmail.com']
        ]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity is not defined.
     */
    public function testThrowsExceptionIfEntityIsNotDefined()
    {
        $this->statement
             ->set(['name' => 'Rafael'])
             ->where(new Filter('id', '=', 1))
             ->getStatement();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Columns is not defined.
     */
    public function testThrowsExceptionIfColumnsIsNotDefined()
    {
        $this->statement
             ->table('user')
             ->where(new Filter('id', '=', 1))
             ->getStatement();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Where clause is not defined.
     */
    public function testThrowsExceptionIfFilterIsNotDefined()
    {
        $this->statement
             ->table('user')
             ->set(['name' => 'Rafael'])
             ->getStatement();
    }

    public function testShouldCreateAnUpdateStatement()
    {
        $statement = 'UPDATE user SET name = ?, surname = ?, email = ? WHERE (id = ?)';
        $params = ['Rafael', 'Felipe', 'manofirmz@gmail.com', 1];
        
        $this->statement
             ->table('user')
             ->set([
                 'name' => 'Rafael', 
                 'surname' => 'Felipe', 
                 'email' => 'manofirmz@gmail.com'
             ])
             ->where(new Filter('id', '=', 1));
        
        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }
}
