<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Component\JsonHelper;

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

    public function setPosition($obj)
    {
        if (is_array($obj))
        {
            $obj = JsonHelper::jsonToObject(json_encode($obj), PositionHeader::class);
        }

        if (!($obj instanceof PositionHeader))
        {
            throw new \TypeError('setPosition should receive PositionHeader');
        }

        $this->Position = $obj;
    }

    public function getPosition()
    {
        return $this->Position;
    }

    public function validate()
    {
        if (!isset($this->Global))
        {
            return false;
        }
        
        if (!isset($this->Position))
        {
            return false;
        }

        return true;
    }
}