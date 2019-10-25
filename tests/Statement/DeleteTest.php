<?php
declare(strict_types=1);

namespace QueryBuilder\Statement;

use PHPUnit\Framework\TestCase;
use QueryBuilder\Filter\Filter;
use \InvalidArgumentException;
use \RuntimeException;

class DeleteTest extends TestCase
{
    /**
     * @var DeleteInterface
     */
    private $statement;

    protected function setUp()
    {
        $this->statement = new Delete();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Entity must be a filled string.
     */
    public function testThrowsExceptionIfEntityIsEmpty()
    {
        $this->statement->from('');
    }

    public function testTheFromMethodShouldReturnInstanceOfDeleteInterface()
    {
        $delete = $this->statement->from('user');
        $this->assertInstanceOf(DeleteInterface::class, $delete);
    }

    public function testTheWhereMethodShouldReturnInstanceOfDeleteInterface()
    {
        $delete = $this->statement->where(new Filter('id', '=', 1));
        $this->assertInstanceOf(DeleteInterface::class, $delete);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Entity is not defined.
     */
    public function testThrowsExceptionIfEntityIsNotDefined()
    {
        $this->statement
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
             ->from('user')
             ->getStatement();
    }

    public function testShouldCreateAnDeleteStatement()
    {
        $statement = 'DELETE FROM user WHERE (id = ?)';
        $params = [1];

        $this->statement
             ->from('user')
             ->where(new Filter('id', '=', 1));
        
        $this->assertEquals($statement, $this->statement->getStatement());
        $this->assertEquals($params, $this->statement->getParams());
    }
}
