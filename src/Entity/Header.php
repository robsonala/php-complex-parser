<?php
namespace PHPComplexParser\Entity;

class Header extends BaseEntity
{
    /**
     * Is global the position of header line
     * TRUE - Global (Just one per file)
     * FALSE - Per block (One for each block)
     * 
     * @var bool
     */
    protected $Global;

    /**
     * @var PositionHeader
     */
    protected $Position;

    public function setGlobal(bool $value)
    {
        $this->Global = $value;
    }

    public function isGlobal()
    {
        return $this->Global;
    }

    public function setPosition(PositionHeader $obj)
    {
        $this->Position = $obj;
    }

    public function getPosition()
    {
        return $this->Position;
    }
}