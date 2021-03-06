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

    public function testName()
    {
        $str = uniqid();
        $this->objEntity->setName($str);

        $this->tester->assertEquals($str, $this->objEntity->getName());
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

    public function testCascadingCall()
    {
        $obj = new Column();

        $this->tester->assertEquals($obj, $obj
            ->setType(1)
            ->setName('lorem')
            ->setKeepHeader(true)
            ->setPosition(new PositionColumn()));

        $this->tester->assertEquals(1, $obj->getType());
        $this->tester->assertEquals('lorem', $obj->getName());
        $this->tester->assertTrue($obj->isKeepHeader());
        $this->tester->assertEquals(new PositionColumn(), $obj->getPosition());
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
        // VALIDATE ON
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);

        $json = json_encode((object)['Type' => $obj->getType(), 'Position' => json_decode($obj->getPosition()->getJson(true))]);

        $this->tester->assertEquals($json, $obj->getJson(true));

        // VALIDATE OFF
        (new Column())->getJson(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Column())->getJson(true);
    }
    
    public function testSetJsonValid()
    {
        // VALIDATE ON
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);

        $json = json_encode((object)['Type' => $obj->getType(), 'Position' => json_decode($obj->getPosition()->getJson(true))]);

        $newObj = new Column();

        $this->tester->assertEquals($obj, $newObj->setJson($json, true));
        $this->tester->assertEquals($obj->getType(), $newObj->getType());
        $this->tester->assertEquals($obj->getPosition(), $newObj->getPosition());

        // VALIDATE OFF
        (new Column())->setJson('{}', false);

    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        (new Column())->setJson('{}', true);
    }
}