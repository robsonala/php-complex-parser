<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity,Header,Position,PositionHeader};

class HeaderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Header
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Header();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Header);
    }

    public function testGlobal()
    {
        $this->objEntity->setGlobal(true);
        $this->tester->assertTrue($this->objEntity->isGlobal());

        $this->objEntity->setGlobal(false);
        $this->tester->assertFalse($this->objEntity->isGlobal());
    }

    public function testPositionValid()
    {
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

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
        $obj = new Header();

        // INVALID
        $this->tester->assertFalse($obj->validate());

        // INVALID [Missing Position when Global is true]
        $obj->setGlobal(true);
        $this->tester->assertFalse($obj->validate());

        // VALID
        $obj->setGlobal(false);
        $this->tester->assertTrue($obj->validate());

        // VALID
        $obj->setGlobal(true);
        $obj->setPosition(new PositionHeader());
        $this->tester->assertTrue($obj->validate());
    }

    public function testGetJsonValid()
    {
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

        $obj = new Header();
        $obj->setGlobal(true);
        $obj->setPosition($pos);

        $json = json_encode((object)['Global' => $obj->isGlobal(), 'Position' => json_decode($pos->getJson(null))]);

        $this->tester->assertEquals($json, $obj->getJson(null));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Header())->getJson(null);
    }
    
    public function testSetJsonValid()
    {
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

        $obj = new Header();
        $obj->setGlobal(true);
        $obj->setPosition($pos);

        $json = json_encode((object)['Global' => $obj->isGlobal(), 'Position' => json_decode($pos->getJson(null))]);

        $newObj = new Header();

        $this->tester->assertEquals($obj, $newObj->setJson($json));
        $this->tester->assertEquals($obj->isGlobal(), $newObj->isGlobal());
        $this->tester->assertEquals($obj->getPosition(), $newObj->getPosition());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        $obj = new Header();
        $obj->setJson('{}');
    }
}