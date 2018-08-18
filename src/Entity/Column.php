<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Entity\Enum\ColumnType;

class Column extends BaseEntity
{
    /**
     * Type of parser
     * 
     * @var int
     */ 
    protected $Type;

    /**
     * TRUE - Show Original header on result out
     * FALSE - Don't keep original header
     * 
     * @var bool
     */
    protected $KeepHeader;

    /**
     * @var PositionColumn
     */
    protected $Position;

    public function setType(int $value)
    {
        if (!ColumnType::isValid($value))
        {
            throw new \Exception('Invalid value for ColumnType');
        }

        $this->Type = $value;
    }

    public function getType()
    {
        return $this->Type;
    }

    public function setKeepHeader(bool $value)
    {
        $this->KeepHeader = $value;
    }

    public function isKeepHeader()
    {
        return $this->KeepHeader;
    }

    public function setPosition($obj)
    {
        if (is_array($obj))
        {
            $data = $obj;
            $obj = new PositionColumn();
            
            foreach ($data as $key=>$value)
            {
                $method = 'set' . $key;
                if (method_exists ($obj, $method))
                {
                    call_user_func([$obj, $method], $value);
                }
            }
        }

        if (!($obj instanceof PositionColumn))
        {
            throw new \TypeError('setPosition should receive PositionColumn');
        }
        $this->Position = $obj;
    }

    public function getPosition()
    {
        return $this->Position;
    }

    public function validate()
    {
        if (!isset($this->Type) || !isset($this->Position))
        {
            return false;
        }
        
        if ($this->getType() == ColumnType::Single()->getValue())
        {
            if (is_numeric($this->getPosition()->getLine()) && is_numeric($this->getPosition()->getColumn()) && $this->getPosition()->getLine() >= 0 && $this->getPosition()->getColumn() >= 0)
            {
                return true;
            }

            return false;
        }
        
        if ($this->getType() == ColumnType::Multiple()->getValue())
        {
            if ($this->getPosition()->getSearch() && $this->getPosition()->getSearchColumn() && $this->getPosition()->getHeaderMatch())
            {
                return true;
            } 
            if ($this->getPosition()->getSearch() && $this->getPosition()->getSearchColumn() && $this->getPosition()->getRange())
            {
                return true;
            }

            return false;
        }

        return false;
    }
}