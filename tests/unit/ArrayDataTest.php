<?php
use PHPComplexParser\ArrayData;

use PHPComplexParser\Entity\Enum\BlockBreak;
use PHPComplexParser\Entity\{Block, Header, PositionHeader};

class ArrayDataTest extends \Codeception\Test\Unit
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
            [rand(100,999),rand(100,999),rand(100,999)],
            [rand(100,999),rand(100,999),rand(100,999)],
            ['END_' . uniqid()]
        ];
    }

    public function testSetData()
    {
        $data = [
            ['lorem', 'ipsum', 'dolor'],
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new ArrayData();

        $this->tester->assertTrue($obj->setData($data));
    }

    public function testGetData()
    {
        $data = [
            ['lorem', 'ipsum', 'dolor'],
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertEquals($data, $obj->getData());
    }

    public function testSplice()
    {
        $data = [
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new ArrayData();
        $obj->setData(array_merge([['lorem', 'ipsum', 'dolor']], $data));
        $obj->splice(0,1);

        $this->tester->assertEquals($data, $obj->getData());
    }

    /**
     * @expectedException \Exception
     */
    public function testChunksInvalid()
    {
        $block = new Block();
        $block->setBreak(BlockBreak::EmptyLine()->getValue());

        $obj = new ArrayData();
        $obj->createChunks($block);
    }

    public function testChunksBySize()
    {
        $block = new Block();
        $block->setSize(4);

        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertTrue($obj->createChunks($block));
        $this->tester->assertEquals([$block1, $block2], $obj->getChunks());
    }

    public function testChunksByEmptyLine()
    {
        $block = new Block();
        $block->setBreak(BlockBreak::EmptyLine()->getValue());

        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, [['','']], $block2);

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertTrue($obj->createChunks($block));
        $this->tester->assertEquals([$block1, $block2], $obj->getChunks());
    }

    public function testChunksByMatchFirstLine()
    {
        $block = new Block();
        $block->setBreak(BlockBreak::MatchFirstLine()->getValue());
        $block->setBreakSearch('START_.+');
        $block->setBreakSearchColumn(0);

        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertTrue($obj->createChunks($block));
        $this->tester->assertEquals([$block1, $block2], $obj->getChunks());
    }

    public function testChunksByMatchLastLineValid()
    {
        $block = new Block();
        $block->setBreak(BlockBreak::MatchLastLine()->getValue());
        $block->setBreakSearch('END_.+');
        $block->setBreakSearchColumn(0);

        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertTrue($obj->createChunks($block));
        $this->tester->assertEquals([$block1, $block2], $obj->getChunks());
    }

    /**
     * @expectedException \Exception
     */
    public function testChunksByMatchLastLineInvalid()
    {
        $block = new Block();

        $data = array_merge($this->genAlleatoryBlock(), $this->genAlleatoryBlock());

        $obj = new ArrayData();
        $obj->setData($data);
        $obj->createChunks($block);
    }

    public function testUnsetData()
    {
        $data = array_merge($this->genAlleatoryBlock(), $this->genAlleatoryBlock());

        $obj = new ArrayData();
        $obj->setData($data);

        $this->tester->assertTrue($obj->unsetData());
    }

    /**
     * @expectedException \Exception
     */
    public function testGetHeaderInvalidMain()
    {
        $header = new Header();
        $header->setGlobal(true);

        $obj = new ArrayData();
        $obj->setData(array_merge($this->genAlleatoryBlock(), $this->genAlleatoryBlock()));

        $obj->getHeader($header);
    }

    public function testGetHeaderValidMain()
    {
        $pos = new PositionHeader();
        $pos->setLine(0);

        $header = new Header();
        $header->setGlobal(true);
        $header->setPosition($pos);

        $headerLine = ['lorem', 'ipsum', 'dolor'];

        $obj = new ArrayData();
        $obj->setData(array_merge([$headerLine] , $this->genAlleatoryBlock(), $this->genAlleatoryBlock()));

        $this->tester->assertEquals($headerLine, $obj->getHeader($header));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetHeaderInvalidBlock()
    {
        $header = new Header();
        $header->setGlobal(false);

        $block = new Block();
        $block->setSize(4);

        $obj = new ArrayData();
        $obj->setData(array_merge($this->genAlleatoryBlock(), $this->genAlleatoryBlock()));
        $obj->createChunks($block);

        $obj->getHeader($header, 0);
    }

    public function testGetHeaderValidBlock()
    {
        $pos = new PositionHeader();
        $pos->setLine(0);

        $header = new Header();
        $header->setGlobal(false);
        $header->setPosition($pos);

        $block = new Block();
        $block->setSize(4);

        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $obj = new ArrayData();
        $obj->setData(array_merge($block1, $block2));
        $obj->createChunks($block);

        $this->tester->assertEquals($block1[0], $obj->getHeader($header, 0));
    }
}