<?php
namespace Entity;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\Position;

use PHPComplexParser\Component\JsonHelper;

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

    public function testLine()
    {
        $num = rand(100,999);

        $this->objEntity->setLine($num);

        $this->tester->assertEquals($num, $this->objEntity->getLine());
    }

    public function testObjectToJson()
    {   
        $obj = new Position();
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Line' => $obj->getLine()]);

        $this->tester->assertEquals($json, JsonHelper::objectToJson($obj));
    }
    
    public function testJsonToObject()
    {   
        $obj = new Position();
        $obj->setLine(rand(1,10));

        $json = json_encode((object)['Line' => $obj->getLine()]);

        $this->tester->assertEquals($obj, JsonHelper::jsonToObject($json, Position::class));
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
}