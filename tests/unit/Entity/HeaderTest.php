<?php
namespace Entity;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\Header;
use PHPComplexParser\Entity\Position;
use PHPComplexParser\Entity\PositionHeader;

use PHPComplexParser\Component\JsonHelper;

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
    public function testPositionInvalidValid()
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

    public function testObjectToJson()
    {   
        $obj = new Header();
        $obj->setGlobal(true);

        $json = json_encode((object)['Global' => $obj->isGlobal()]);

        $this->tester->assertEquals($json, JsonHelper::objectToJson($obj));
    }
    
    public function testJsonToObject()
    {   
        $obj = new Header();
        $obj->setGlobal(true);

        $json = json_encode((object)['Global' => $obj->isGlobal()]);

        $this->tester->assertEquals($obj, JsonHelper::jsonToObject($json, Header::class));
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
}