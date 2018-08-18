<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Entity\Enum\BlockBreak;

class Block extends BaseEntity
{
    /**
     * Transpose before use matrix
     * 
     * @var bool
     */
    protected $Transpose;

    /**
     * Size of the block (number in lines)
     * 
     * @var int
     */ 
    protected $Size;

    /**
     * Type of break (if it's not size fixed)
     * 
     * @var int
     */ 
    protected $Break;

    /**
     * String|Regex must be find to start new block
     * 
     * @var string
     */ 
    protected $BreakSearch;

    /**
     * Column position to do the search
     * 
     * @var int
     */ 
    protected $BreakSearchColumn;

    public function setTranspose(bool $value)
    {
        $this->Transpose = $value;
    }

    public function isTranspose()
    {
        return $this->Transpose;
    }

    public function setSize(int $value)
    {
        if ($value <= 0)
        {
            throw new \Exception('Invalid Size');
        }

        $this->Size = $value;
    }

    public function getSize()
    {
        return $this->Size;
    }

    public function setBreak(int $value)
    {
        if (!BlockBreak::isValid($value))
        {
            throw new \Exception('Invalid value for BlockBreak');
        }

        $this->Break = $value;
    }

    public function getBreak()
    {
        return $this->Break;
    }

    public function setBreakSearch(string $value)
    {
        $this->BreakSearch = $value;
    }

    public function getBreakSearch()
    {
        return $this->BreakSearch;
    }

    public function setBreakSearchColumn(int $value)
    {
        if ($value < 0)
        {
            throw new \Exception('Invalid BreakSearchColumn');
        }

        $this->BreakSearchColumn = $value;
    }

    public function getBreakSearchColumn()
    {
        return $this->BreakSearchColumn;
    }

    public function validate()
    {
        if (!isset($this->Size) && !isset($this->Break))
        {
            return false;
        }
        if (isset($this->Break) && in_array($this->getBreak(), [BlockBreak::MatchLastLine()->getValue(), BlockBreak::MatchFirstLine()->getValue()]))
        {
            if (!isset($this->BreakSearch) || !isset($this->BreakSearchColumn))
            {
                return false;
            }   
        }

        return true;
    }
}