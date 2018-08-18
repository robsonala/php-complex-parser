<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity,Position};

class PositionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Position
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Position();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Position);
    }

    public function testLineValid()
    {
        $num = rand(100,999);

        $this->objEntity->setLine($num);

        $this->tester->assertEquals($num, $this->objEntity->getLine());
    }

    /**
     * @expectedException \Exception
     */
    public function testLineInvalid()
    {
        $this->objEntity->setLine(-1);
    }

    public function testValidate()
    {
        $obj = new Position();

        // INVALID
        $this->tester->assertFalse($obj->validate());

        // VALID
        $obj->setLine(rand(1,10));
        $this->tester->assertTrue($obj->validate());
    }

    public function testGetJsonValid()
    {   
        $obj = new Position();
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Line' => $obj->getLine()]);

        $this->tester->assertEquals($json, $obj->getJson(null));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Position())->getJson(null);
    }
    
    public function testSetJsonValid()
    {
        $obj = new Position();
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Line' => $obj->getLine()]);

        $newObj = new Position();

        $this->tester->assertEquals($obj, $newObj->setJson($json));
        $this->tester->assertEquals($obj->getLine(), $newObj->getLine());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        $obj = new Position();
        $obj->setJson('{}');
    }
}