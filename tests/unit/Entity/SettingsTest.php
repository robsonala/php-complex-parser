<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;

class SettingsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Settings
     */
    protected $settings;
    
    protected function _before()
    {
        $this->settings = new Settings();
    }

    protected function _after()
    {
    }

    protected function genValidHeader()
    {
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

        $obj = new Header();
        $obj->setGlobal(true);
        $obj->setPosition($pos);

        return $obj;
    }

    protected function genValidBlock()
    {
        $obj = new Block();
        $obj->setBreak(BlockBreak::EmptyLine()->getValue());
        
        return $obj;
    }

    protected function genValidColumn()
    {
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $obj = new Column();
        $obj->setType(ColumnType::Single()->getValue());
        $obj->setPosition($pos);
        
        return $obj;
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Settings);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetHeaderInvalid()
    {   
        $this->settings->setHeader(new Header());
    }

    public function testSetHeaderValid()
    {   
        $header = $this->genValidHeader();
        $this->settings->setHeader($header);

        $this->tester->assertEquals($header, $this->settings->getHeader());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetBlockInvalid()
    {   
        $this->settings->setBlock(new Block());
    }

    public function testSetBlockValid()
    {   
        $block = $this->genValidBlock();
        $this->settings->setBlock($block);

        $this->tester->assertEquals($block, $this->settings->getBlock());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetColumnsInvalid()
    {   
        $this->settings->setColumns(new Columns());
    }

    public function testSetColumnsValid()
    {   
        $cols = new Columns();

        $obj1 = $this->genValidColumn();
        $cols->add($obj1);

        $obj2 = $this->genValidColumn();
        $cols->add($obj2);

        $this->settings->setColumns($cols);

        $this->tester->assertEquals($cols, $this->settings->getColumns());
    }

    public function testValidate()
    {
        $obj = new Settings();

        // INVALID
        $this->tester->assertFalse($obj->validate());

        // VALID
        $cols = new Columns();
        $cols->add($this->genValidColumn());
        
        $obj->setHeader($this->genValidHeader());
        $obj->setBlock($this->genValidBlock());
        $obj->setColumns($cols);

        $this->tester->assertTrue($obj->validate());
    }

    /**
     * @expectedException \Exception
     */
    public function testGetJsonInvalid()
    {   
        (new Settings())->getJson(true);
    }

    public function testGetJsonValid()
    {   
        // VALIDATE ON
        $col = $this->genValidColumn();
        $cols = new Columns();
        $cols->add($col);

        $header = $this->genValidHeader();
        $block = $this->genValidBlock();

        $obj = new Settings();
        $obj->setHeader($header);
        $obj->setBlock($block);
        $obj->setColumns($cols);

        $json = json_encode((object)[
            'Header' => json_decode($obj->getHeader()->getJson(true)),
            'Block' => json_decode($obj->getBlock()->getJson(true)),
            'Columns' => [json_decode($col->getJson(true))]
        ]);

        $this->tester->assertEquals($json, $obj->getJson(true));

        // VALIDATE OFF
        (new Settings())->getJson(false);
    }

    /**
     * @expectedException \Exception
     */
    public function testSetJsonInvalid()
    {   
        (new Settings())->setJson('{}', true);
    }

    public function testSetJsonValid()
    {
        // VALIDATE ON
        $col = $this->genValidColumn();
        $cols = new Columns();
        $cols->add($col);

        $header = $this->genValidHeader();
        $block = $this->genValidBlock();

        $obj = new Settings();
        $obj->setHeader($header);
        $obj->setBlock($block);
        $obj->setColumns($cols);

        $json = json_encode((object)[
            'Header' => json_decode($obj->getHeader()->getJson(true)),
            'Block' => json_decode($obj->getBlock()->getJson(true)),
            'Columns' => [json_decode($col->getJson(true))]
        ]);

        $newObj = new Settings();

        $this->tester->assertEquals($obj, $newObj->setJson($json, true));
        $this->tester->assertEquals($obj->getHeader(), $newObj->getHeader());
        $this->tester->assertEquals($obj->getBlock(), $newObj->getBlock());
        $this->tester->assertEquals($obj->getColumns(), $newObj->getColumns());

        // VALIDATE OFF
        (new Settings())->setJson('{}', false);
    }
    
}