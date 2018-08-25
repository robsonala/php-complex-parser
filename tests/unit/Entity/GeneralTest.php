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

    public function testCascadingCall()
    {
        $obj = new General();

        $this->tester->assertEquals($obj, $obj->setIgnoreLinesBegin(1));
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
        // VALIDATE ON
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $this->tester->assertEquals($json, $obj->getJson(true));

        // VALIDATE OFF
        (new General())->getJson(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new General())->getJson(true);
    }
    
    public function testSetJsonValid()
    {
        // VALIDATE ON
        $obj = new General();
        $obj->setIgnoreLinesBegin(rand(1,10));

        $json = json_encode((object)['IgnoreLinesBegin' => $obj->getIgnoreLinesBegin()]);

        $newObj = new General();

        $this->tester->assertEquals($obj, $newObj->setJson($json, true));
        $this->tester->assertEquals($obj->getIgnoreLinesBegin(), $newObj->getIgnoreLinesBegin());

        // VALIDATE OFF
        (new General())->setJson('{}', false);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        (new General())->setJson('{}', true);
    }

}