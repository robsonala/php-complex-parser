<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity, Block, Enum\BlockBreak};

class BlockTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Block
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Block();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Block);
    }

    public function testTranspose()
    {
        $this->objEntity->setTranspose(true);
        $this->tester->assertTrue($this->objEntity->isTranspose());

        $this->objEntity->setTranspose(false);
        $this->tester->assertFalse($this->objEntity->isTranspose());
    }

    public function testSizeValid()
    {
        $num = rand(100,999);
        $this->objEntity->setSize($num);

        $this->tester->assertEquals($num, $this->objEntity->getSize());
    }

    /**
     * @expectedException \Exception
     */
    public function testSizeInvalid()
    {
        $this->objEntity->setSize(0);
    }

    public function testBreakValid()
    {
        $this->objEntity->setBreak(BlockBreak::EmptyLine()->getValue());

        $this->tester->assertEquals(BlockBreak::EmptyLine()->getValue(), $this->objEntity->getBreak());
    }

    /**
     * @expectedException \Exception
     */
    public function testBreakInvalid()
    {
        $this->objEntity->setBreak(999);
        $this->tester->fail('Expected Expcetion');
    }

    public function testBreakSearch()
    {
        $str = uniqid();
        $this->objEntity->setBreakSearch($str);

        $this->tester->assertEquals($str, $this->objEntity->getBreakSearch());
    }

    public function testBreakSearchColumnValid()
    {
        $num = rand(100,999);
        $this->objEntity->setBreakSearchColumn($num);

        $this->tester->assertEquals($num, $this->objEntity->getBreakSearchColumn());
    }

    /**
     * @expectedException \Exception
     */
    public function testBreakSearchColumnInvalid()
    {
        $this->objEntity->setBreakSearchColumn(-1);
    }

    public function testValidate()
    {
        // INVALID
        $obj = new Block();
        $this->tester->assertFalse($obj->validate());

        // INVALID
        $obj = new Block();
        $obj->setBreak(BlockBreak::MatchLastLine()->getValue());
        $this->tester->assertFalse($obj->validate());

        // VALID
        $obj = new Block();
        $obj->setSize(1);
        $this->tester->assertTrue($obj->validate());

        // VALID
        $obj = new Block();
        $obj->setBreak(BlockBreak::EmptyLine()->getValue());
        $this->tester->assertTrue($obj->validate());

        // VALID
        $obj = new Block();
        $obj->setBreak(BlockBreak::MatchLastLine()->getValue());
        $obj->setBreakSearch(uniqid());
        $obj->setBreakSearchColumn(0);
        $this->tester->assertTrue($obj->validate());
    }

    public function testGetJsonValid()
    {   
        // VALIDATE ON
        $obj = new Block();
        $obj->setSize(rand(1,10));

        $json = json_encode((object)['Size' => $obj->getSize()]);

        $this->tester->assertEquals($json, $obj->getJson(true));

        // VALIDATE OFF
        (new Block())->getJson(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Block())->getJson(true);
    }
    
    public function testSetJsonValid()
    {
        // VALIDATE ON
        $obj = new Block();
        $obj->setSize(rand(1,10));

        $json = json_encode((object)['Size' => $obj->getSize()]);

        $newObj = new Block();

        $this->tester->assertEquals($obj, $newObj->setJson($json, true));
        $this->tester->assertEquals($obj->getSize(), $newObj->getSize());

        // VALIDATE OFF
        (new Block())->setJson('{}', false);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        (new Block())->setJson('{}', true);
    }
}