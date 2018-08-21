<?php
namespace PHPComplexParser;

use PHPComplexParser\Component\ArrayChunker;
use PHPComplexParser\Entity\Block;
use PHPComplexParser\Entity\Enum\BlockBreak;

class ArrayData
{
    /**
     * Array value
     * 
     * @var array
     */
    protected $Data;

    /**
     * Chunks
     * 
     * @var array
     */
    protected $Chunks;

    public function setData(array $data) : bool
    {
        $this->Data = $data;

        return true;
    }

    public function getData() : array
    {
        return $this->Data;
    }

    public function splice(int $offset, int $length = null)
    {
        if ($length == null)
        {
            $length = count($this->Data);
        }
        
        return array_splice($this->Data, $offset, $length);
    }

    public function createChunks(Block $block) : bool
    {
        if (!isset($this->Data))
        {
            throw new \Exception('Data has not been set');
        }

        if (!$block->validate())
        {
            throw new \Exception('Invalid Block settings');
        }

        if ($block->getSize())
        {
            $this->Chunks = ArrayChunker::bySize($block->getSize(), $this->Data);
        } else {
            switch ($block->getBreak())
            {
                case BlockBreak::EmptyLine()->getValue():
                    $this->Chunks = ArrayChunker::byEmptyLine($this->Data);
                    break;
                case BlockBreak::MatchFirstLine()->getValue():
                    $this->Chunks = ArrayChunker::byMatchFirstLine($block->getBreakSearch(), $block->getBreakSearchColumn(), $this->Data);
                    break;
                case BlockBreak::MatchLastLine()->getValue():
                    $this->Chunks = ArrayChunker::byMatchLastLine($block->getBreakSearch(), $block->getBreakSearchColumn(), $this->Data);
                    break;
            }
        }

        return !!$this->Chunks;
    }

    public function getChunks()
    {
        return $this->Chunks;
    }
}