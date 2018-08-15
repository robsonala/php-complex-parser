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
        $this->Line = $value;
    }

    public function getLine()
    {
        return $this->Line;
    }
}