<?php
namespace Entity;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\General;

use PHPComplexParser\Component\JsonHelper;

class GeneralTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var General
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new General();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new General);
    }

    public function testIgnoreLinesBegin()
    {
        $num = rand(100,999);
        $this->objEntity->setIgnoreLinesBegin($num);

        $this->tester->assertEquals($num, $this->objEntity->getIgnoreLinesBegin());
    }

    public function testObjectToJson()
    {   
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $this->tester->assertEquals($json, JsonHelper::objectToJson($obj));
    }
    
    public function testJsonToObject()
    {   
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $this->tester->assertEquals($obj, JsonHelper::jsonToObject($json, General::class));
    }

    public function testValidate()
    {
        $obj = new General();

        // INVALID
        $this->tester->assertFalse($obj->validate());

        // VALID
        $obj->setIgnoreLinesBegin(rand(1,10));
        $this->tester->assertTrue($obj->validate());
    }
}