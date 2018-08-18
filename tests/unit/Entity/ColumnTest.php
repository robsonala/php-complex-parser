<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity, Position, PositionColumn, Column, Enum\ColumnType};

class ColumnTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Column
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Column();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Column);
    }

    public function testTypeValid()
    {
        $this->objEntity->setType(ColumnType::Single()->getValue());

        $this->tester->assertEquals(ColumnType::Single()->getValue(), $this->objEntity->getType());
    }

    /**
     * @expectedException \Exception
     */
    public function testTypeInvalid()
    {
        $this->objEntity->setType(999);
        $this->tester->fail('Expected Expcetion');
    }

    public function testKeepHeader()
    {
        $this->objEntity->setKeepHeader(true);

        $this->tester->assertTrue($this->objEntity->isKeepHeader());
    }

    public function testPositionValid()
    {
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $this->objEntity->setPosition($pos);
        $this->tester->assertEquals($pos, $this->objEntity->getPosition());
    }

    /**
     * @expectedException \Exception
     */
    public function testPositionInvalid()
    {
        $pos = new Position();
        $pos->setLine(rand(1,9));

        try {
            $this->objEntity->setPosition($pos);
            $this->tester->fail('Expected TypeError');
        } catch (\Exception $e) {
            $this->tester->fail('Expected TypeError');
        } catch (\Error $e) {
            throw new \Exception();
        }
    }

    public function testValidate()
    {
        // INVALID
        $obj = new Column();
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Multiple()->getValue());
        $obj->setPosition($pos);
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $pos = new PositionColumn();
        $pos->setSearch(uniqid());
        $pos->setSearchColumn(rand(1,10));
        $pos->setHeaderMatch(uniqid());

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);
        $this->tester->assertFalse($obj->validate());

        // VALID
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);
        $this->tester->assertTrue($obj->validate());

        // VALID
        $pos = new PositionColumn();
        $pos->setSearch(uniqid());
        $pos->setSearchColumn(rand(1,10));
        $pos->setHeaderMatch(uniqid());

        $obj = new Column();
        $obj->setType(ColumnType::Multiple()->getValue());
        $obj->setPosition($pos);
        $this->tester->assertTrue($obj->validate());
    }

    public function testGetJsonValid()
    {   
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);

        $json = json_encode((object)['Type' => $obj->getType(), 'Position' => json_decode($obj->getPosition()->getJson(null))]);

        $this->tester->assertEquals($json, $obj->getJson(null));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Column())->getJson(null);
    }
    
    public function testSetJsonValid()
    {
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);

        $json = json_encode((object)['Type' => $obj->getType(), 'Position' => json_decode($obj->getPosition()->getJson(null))]);

        $newObj = new Column();

        $this->tester->assertEquals($obj, $newObj->setJson($json));
        $this->tester->assertEquals($obj->getType(), $newObj->getType());
        $this->tester->assertEquals($obj->getPosition(), $newObj->getPosition());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        $obj = new Column();
        $obj->setJson('{}');
    }
}