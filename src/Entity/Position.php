<?php
namespace PHPComplexParser\Entity;

class Position extends BaseEntity
{
    /**
     * Line Position
     * 
     * @var int
     */
    protected $Line;
    
    public function setLine(int $value)
    {
        if ($value < 0)
        {
            throw new \Exception('Invalid Line');
        }
        
        $this->Line = $value;

        return $this;
    }

    public function getLine()
    {
        return $this->Line;
    }

    public function validate()
    {
        if (!isset($this->Line))
        {
            return false;
        }

        return true;
    }
}