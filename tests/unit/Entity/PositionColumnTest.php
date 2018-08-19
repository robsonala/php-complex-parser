<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity,Position,PositionColumn};

class PositionColumnsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var PositionColumn
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new PositionColumn();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new PositionColumn);
    }

    public function testColumnValid()
    {
        $num = rand(100,999);
        $this->objEntity->setColumn($num);

        $this->tester->assertEquals($num, $this->objEntity->getColumn());
    }

    /**
     * @expectedException \Exception
     */
    public function testColumnInvalid()
    {
        $this->objEntity->setColumn(-1);
    }

    public function testSearch()
    {
        $str = uniqid();
        $this->objEntity->setSearch($str);

        $this->tester->assertEquals($str, $this->objEntity->getSearch());
    }

    public function testSearchColumnValid()
    {
        $num = rand(100,999);
        $this->objEntity->setSearchColumn($num);

        $this->tester->assertEquals($num, $this->objEntity->getSearchColumn());
    }

    /**
     * @expectedException \Exception
     */
    public function testSearchColumnInvalid()
    {
        $this->objEntity->setSearchColumn(-1);
    }

    public function testHeaderMatch()
    {
        $str = uniqid();
        $this->objEntity->setHeaderMatch($str);

        $this->tester->assertEquals($str, $this->objEntity->getHeaderMatch());
    }

    public function testRangeValid()
    {
        $num = rand(100,499);
        $num2 = rand(500,999);
        $this->objEntity->setRange($num, $num2);

        $this->tester->assertEquals([$num, $num2], $this->objEntity->getRange());

        $this->objEntity->setRange($num, null);

        $this->tester->assertEquals([$num, null], $this->objEntity->getRange());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testRangeInvalid()
    {
        try {
            $this->objEntity->setRange(null, null);
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
        $obj = new PositionColumn();
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $obj = new PositionColumn();
        $obj->setLine(rand(1,10));
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $obj = new PositionColumn();
        $obj->setSearch(uniqid());
        $obj->setSearchColumn(rand(1,10));
        $this->tester->assertFalse($obj->validate());

        // VALID
        $obj = new PositionColumn();
        $obj->setLine(rand(1,10));
        $obj->setColumn(rand(1,10));
        $this->tester->assertTrue($obj->validate());

        // VALID
        $obj = new PositionColumn();
        $obj->setSearch(uniqid());
        $obj->setSearchColumn(rand(1,10));
        $obj->setHeaderMatch(uniqid());
        $this->tester->assertTrue($obj->validate());

        // VALID
        $obj = new PositionColumn();
        $obj->setSearch(uniqid());
        $obj->setSearchColumn(rand(1,10));
        $obj->setRange(rand(0,10), null);
        $this->tester->assertTrue($obj->validate());
    }

    public function testGetJsonValid()
    {   
        // VALIDATE ON
        $obj = new PositionColumn();
        $obj->setColumn(rand(1,10));
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Column' => $obj->getColumn(), 'Line' => $obj->getLine()]);

        $this->tester->assertEquals($json, $obj->getJson(true));

        // VALIDATE OFF
        (new PositionColumn())->getJson(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new PositionColumn())->getJson(true);
    }

    public function testSetJsonValid()
    {
        // VALIDATE ON
        $obj = new PositionColumn();
        $obj->setColumn(rand(1,10));
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Column' => $obj->getColumn(), 'Line' => $obj->getLine()]);

        $newObj = new PositionColumn();

        $this->tester->assertEquals($obj, $newObj->setJson($json, true));
        $this->tester->assertEquals($obj->getLine(), $newObj->getLine());
        $this->tester->assertEquals($obj->getColumn(), $newObj->getColumn());

        // VALIDATE OFF
        (new PositionColumn())->setJson('{}', false);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        (new PositionColumn())->setJson('{}', true);
    }

}