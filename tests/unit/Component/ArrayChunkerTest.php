<?php
namespace Component;

use PHPComplexParser\Component\ArrayChunker;

class ArrayChunkerTest extends \Codeception\Test\Unit
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

    public function testBySize()
    {
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::bySize(4, $data));
    }

    public function testByEmptyLine()
    {
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, [['','']], $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byEmptyLine($data));
    }

    public function testByMatchFirstLine()
    {
        // First column search
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchFirstLine('START_.+', 0, $data));

        // Second column search
        $block1 = array_merge([['','START_' . uniqid()]], $this->genAlleatoryBlock());
        $block2 = array_merge([['','START_' . uniqid()]], $this->genAlleatoryBlock());

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchFirstLine('START_.+', 1, $data));

        // Whole column search
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchFirstLine('START_.+', null, $data));
    }

    public function testByMatchLastLine()
    {
        // First column search
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchLastLine('END_.+', 0, $data));

        // Second column search
        $block1 = array_merge($this->genAlleatoryBlock(), [['','END_' . uniqid()]]);
        $block2 = array_merge($this->genAlleatoryBlock(), [['','END_' . uniqid()]]);

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchLastLine('END_.+', 1, $data));

        // Whole column search
        $block1 = $this->genAlleatoryBlock();
        $block2 = $this->genAlleatoryBlock();

        $data = array_merge($block1, $block2);

        $this->tester->assertEquals([$block1, $block2], ArrayChunker::byMatchLastLine('END_.+', null, $data));
    }
}