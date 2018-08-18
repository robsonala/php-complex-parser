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

    public function setPosition($obj)
    {
        if (is_array($obj))
        {
            $data = $obj;
            $obj = new PositionHeader();
            
            foreach ($data as $key=>$value)
            {
                $method = 'set' . $key;
                if (method_exists ($obj, $method))
                {
                    call_user_func([$obj, $method], $value);
                }
            }
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
        
        if ($this->isGlobal() && !isset($this->Position))
        {
            return false;
        }

        return true;
    }
}