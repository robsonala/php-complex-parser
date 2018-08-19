<?php

use PHPComplexParser\PHPComplexParser;
use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;

class PHPComplexParserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function genSettings()
    {
        // HEADER
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

        $header = new Header();
        $header->setGlobal(true);
        $header->setPosition($pos);

        // BLOCK
        $block = new Block();
        $block->setBreak(BlockBreak::EmptyLine()->getValue());

        // COLUMN
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $column = new Column();
        $column->setType(ColumnType::Single()->getValue());
        $column->setPosition($pos);

        // COLUMNS
        $columns = new Columns();
        $columns->add($column);

        // SETTINGS
        $settings = new Settings();
        $settings->setHeader($header);
        $settings->setBlock($block);
        $settings->setColumns($columns);

        return $settings;
    }

    /**
     * @expectedException \Exception
     */
    public function testLoadSettingsJsonInvalid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertFalse($obj->loadSettingsJson('{}'));
    }

    public function testLoadSettingsJsonValid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertTrue($obj->loadSettingsJson($this->genSettings()->getJson(true)));
    }

    public function testLoadSettingsValid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertTrue($obj->loadSettings($this->genSettings()));
    }
}