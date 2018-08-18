<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity, General};

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

    /**
     * @expectedException \Exception
     */
    public function testIgnoreLinesBeginInvalid()
    {
        $this->objEntity->setIgnoreLinesBegin(-1);
    }

    public function testIgnoreLinesBeginValid()
    {
        $num = rand(100,999);
        $this->objEntity->setIgnoreLinesBegin($num);

        $this->tester->assertEquals($num, $this->objEntity->getIgnoreLinesBegin());
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

    public function testGetJsonValid()
    {   
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $this->tester->assertEquals($json, $obj->getJson(null));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new General())->getJson(null);
    }
    
    public function testSetJsonValid()
    {
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $newObj = new General();

        $this->tester->assertEquals($obj, $newObj->setJson($json));
        $this->tester->assertEquals($obj->getIgnoreLinesBegin(), $newObj->getIgnoreLinesBegin());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        $obj = new General();
        $obj->setJson('{}');
    }

}