<?php
use PHPComplexParser\Extractor;

use PHPComplexParser\Entity\Enum\ColumnType;
use PHPComplexParser\Entity\{Column, PositionColumn};

class ExtractorTest extends \Codeception\Test\Unit
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

    private function genAlleatoryBlock()
    {
        return [
            ['START_' . uniqid()],
            ['Stock', rand(100,999),rand(100,999),rand(100,999)],
            ['Sales', rand(100,999),rand(100,999),rand(100,999)],
            ['END_' . uniqid()]
        ];
    }

    public function testSingle()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();

        $pos = new PositionColumn();
        $pos->setLine(0);
        $pos->setColumn(0);

        $col = new Column();
        $col->setType(ColumnType::Single()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        $this->tester->assertEquals([$name => $block1[0][0]], Extractor::single($block1[0], $col));
    }

    public function testMultipleRangeWithoutHeader()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();

        $pos = new PositionColumn();
        $pos->setSearch('Stock');
        $pos->setSearchColumn(0);
        $pos->setRange(1, null);

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        $this->tester->assertEquals([$name => array_slice($block1[1], 1, 3)], Extractor::multipleRange($block1[1], $col));
    }

    public function testMultipleRangeWithHeader()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();
        $header = ['', 'm1', 'm2', 'm3'];

        $pos = new PositionColumn();
        $pos->setSearch('Sales');
        $pos->setSearchColumn(0);
        $pos->setRange(1, null);

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setKeepHeader(true);
        $col->setPosition($pos);

        $expected = [$name => [
            'm1' => $block1[1][1],
            'm2' => $block1[1][2],
            'm3' => $block1[1][3]
        ]];

        $this->tester->assertEquals($expected, Extractor::multipleRange($block1[1], $col, $header));
    }

    public function testMultipleHeaderMatchWithoutHeader()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();
        $header = ['', 'm1', 'm2', 'm3'];

        $pos = new PositionColumn();
        $pos->setSearch('Sales');
        $pos->setSearchColumn(0);
        $pos->setHeaderMatch('m\d+');

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        $this->tester->assertEquals([$name => array_slice($block1[2], 1, 3)], Extractor::multipleHeaderMatch($block1[2], $col, $header));
    }

    public function testMultipleHeaderMatchWithHeader()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();
        $header = ['', 'm1', 'm2', 'm3'];

        $pos = new PositionColumn();
        $pos->setSearch('Sales');
        $pos->setSearchColumn(0);
        $pos->setHeaderMatch('m\d+');

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setKeepHeader(true);
        $col->setPosition($pos);

        $expected = [$name => [
            'm1' => $block1[2][1],
            'm2' => $block1[2][2],
            'm3' => $block1[2][3]
        ]];

        $this->tester->assertEquals($expected, Extractor::multipleHeaderMatch($block1[2], $col, $header));
    }

    public function testGetDataFromLine_Single()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();

        $pos = new PositionColumn();
        $pos->setLine(0);
        $pos->setColumn(0);

        $col = new Column();
        $col->setType(ColumnType::Single()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        // Result found
        $this->tester->assertEquals([$name => $block1[0][0]], Extractor::getDataFromLine(0, $block1[0], $col));
        
        // Result not found
        $this->tester->assertNull(Extractor::getDataFromLine(1, $block1[1], $col));
    }

    public function testGetDataFromLine_MultipleRange()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();

        $pos = new PositionColumn();
        $pos->setSearch('Stock');
        $pos->setSearchColumn(0);
        $pos->setRange(1, null);

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        // Result found
        $this->tester->assertEquals([$name => array_slice($block1[1], 1, 3)], Extractor::getDataFromLine(1, $block1[1], $col));

        // Result not found
        $this->tester->assertNull(Extractor::getDataFromLine(0, $block1[0], $col));
    }

    public function testGetDataFromLine_HeaderMatch()
    {
        $block1 = $this->genAlleatoryBlock();
        $name = uniqid();
        $header = ['', 'm1', 'm2', 'm3'];

        $pos = new PositionColumn();
        $pos->setSearch('Sales');
        $pos->setSearchColumn(0);
        $pos->setHeaderMatch('m\d+');

        $col = new Column();
        $col->setType(ColumnType::Multiple()->getValue());
        $col->setName($name);
        $col->setPosition($pos);

        // Result found
        $this->tester->assertEquals([$name => array_slice($block1[2], 1, 3)], Extractor::getDataFromLine(2, $block1[2], $col, $header));

        // Result not found
        $this->tester->assertNull(Extractor::getDataFromLine(0, $block1[0], $col, $header));
    
    }
}