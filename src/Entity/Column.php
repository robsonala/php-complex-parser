<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Entity\Enum\ColumnType;
use PHPComplexParser\Component\JsonHelper;

class Column extends BaseEntity
{
    /**
     * Type of parser
     * 
     * @var int
     */ 
    protected $Type;

    /**
     * Name used on result out
     * 
     * @var string
     */ 
    protected $Name;

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

        return $this;
    }

    public function getType()
    {
        return $this->Type;
    }

    public function setName(string $value)
    {
        $this->Name = $value;

        return $this;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setKeepHeader(bool $value)
    {
        $this->KeepHeader = $value;

        return $this;
    }

    public function isKeepHeader()
    {
        return $this->KeepHeader;
    }

    public function setPosition($obj)
    {
        if (is_array($obj))
        {
            $obj = JsonHelper::jsonToObject(json_encode($obj), PositionColumn::class);
        }

        if (!($obj instanceof PositionColumn))
        {
            throw new \TypeError('setPosition should receive PositionColumn');
        }
        
        $this->Position = $obj;

        return $this;
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
            if ($this->getPosition()->getSearch() && $this->getPosition()->getSearchColumn() >= 0 && $this->getPosition()->getHeaderMatch())
            {
                return true;
            } 
            if ($this->getPosition()->getSearch() && $this->getPosition()->getSearchColumn() >= 0 && $this->getPosition()->getRange())
            {
                return true;
            }
            if ($this->getPosition()->getLine() && $this->getPosition()->getRange())
            {
                return true;
            }

            return false;
        }

        return false;
    }
}