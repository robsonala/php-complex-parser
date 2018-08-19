<?php
namespace Repository;

use PHPComplexParser\Entity\{BaseEntity, Position, PositionColumn, Column, Enum\ColumnType};
use PHPComplexParser\Repository\Columns;

class ColumnsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _after()
    {
    }

    protected function genValidColumn()
    {
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $col = new Column();
        $col->setType(ColumnType::Single()->getValue());
        $col->setPosition($pos);
        $col->setName(uniqid());

        return $col;
    }

    public function testGetAllEmpty()
    {
        $repo = new Columns();
        $this->tester->assertEquals([], $repo->getAll());
    }

    public function testAddValid()
    {
        $repo = new Columns();

        $col = $this->genValidColumn();

        $this->tester->assertTrue($repo->add($col));
    }

    public function testAddWrongTypeParameter()
    {
        $repo = new Columns();

        $obj = new PositionColumn();
        $obj->setLine(rand(1,9));
        $obj->setColumn(rand(1,9));

        $this->tester->assertFalse($repo->add($obj));
    }

    public function testAddInvalidColumn()
    {
        $repo = new Columns();
        $obj = new Column();

        $this->tester->assertFalse($repo->add($obj));
    }

    public function testGetAll()
    {
        $repo = new Columns();
        
        $col = $this->genValidColumn();

        $repo->add($col);

        $this->tester->assertCount(1, $repo->getAll());
        $this->tester->assertEquals([$col], $repo->getAll());
    }

    public function testExists()
    {
        $repo = new Columns();

        $col = $this->genValidColumn();
        $repo->add($col);

        $this->tester->assertTrue($repo->exists($col->getName()));
        $this->tester->assertFalse($repo->exists(uniqid()));
    }

    public function testFindWithResult()
    {
        $repo = new Columns();

        $col = $this->genValidColumn();

        $repo->add($col);

        $this->tester->assertEquals($col, $repo->find($col->getName()));
    }

    public function testFindWithoutResult()
    {
        $repo = new Columns();

        $this->tester->assertNull($repo->find(''));
        $this->tester->assertNull($repo->find(uniqid()));
    }

    public function testRemoveExists()
    {
        $repo = new Columns();

        $col1 = $this->genValidColumn();
        $repo->add($col1);
        $col2 = $this->genValidColumn();
        $repo->add($col2);

        $this->tester->assertTrue($repo->remove($col1->getName()));

        $this->tester->assertNull($repo->find($col1->getName()));

        $this->tester->assertEquals([$col2], $repo->getAll());
    }
}