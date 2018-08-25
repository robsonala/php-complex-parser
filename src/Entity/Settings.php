<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Repository\Columns;
use PHPComplexParser\Component\JsonHelper;

class Settings extends BaseEntity
{
    /**
     * General Settings
     * 
     * @var General
     */ 
    protected $General;
    
    /**
     * Header Settings
     * 
     * @var Header
     */ 
    protected $Header;

    /**
     * Block Settings
     * 
     * @var Block
     */ 
    protected $Block;

    /**
     * List of Columns Settings
     * 
     * @var Columns
     */ 
    protected $Columns;

    public function setGeneral(General $obj)
    {
        if (!$obj->validate())
        {
            throw new \Exception('Invalid General');
        }

        $this->General = $obj;
    }

    public function getGeneral()
    {
        return $this->General;
    }

    public function setHeader(Header $obj)
    {
        if (!$obj->validate())
        {
            throw new \Exception('Invalid Header');
        }

        $this->Header = $obj;
    }

    public function getHeader()
    {
        return $this->Header;
    }

    public function setBlock(Block $obj)
    {
        if (!$obj->validate())
        {
            throw new \Exception('Invalid Block');
        }

        $this->Block = $obj;
    }

    public function getBlock()
    {
        return $this->Block;
    }

    public function setColumns(Columns $list)
    {
        if ($list->count() <= 0)
        {
            throw new \Exception('Invalid Columns');
        }

        $this->Columns = $list;
    }

    public function getColumns()
    {
        return $this->Columns;
    }

    public function validate()
    {
        if (!isset($this->Header) || !isset($this->Block) || !isset($this->Columns))
        {
            return false;
        }

        return true;
    }

    /**
     * @Override
     */
    public function getJson(bool $runValidate)
    {
        if ($runValidate && !$this->validate())
        {
            throw new \Exception('This Entity formation is not valid');
        }

        $columnsOut = [];
        if (isset($this->Columns) && $this->getColumns()->count() > 0)
        {
            $columns = $this->getColumns()->getAll();
            foreach ($columns as $column)
            {
                $columnsOut[] = json_decode($column->getJson(true));
            }
        }

        $outArray = new \stdClass();

        if (isset($this->General))
        {
            $outArray->General = json_decode($this->getGeneral()->getJson(true));
        }
        if (isset($this->Header))
        {
            $outArray->Header = json_decode($this->getHeader()->getJson(true));
        }
        if (isset($this->Block))
        {
            $outArray->Block = json_decode($this->getBlock()->getJson(true));
        }
        if (count($columnsOut) > 0)
        {
            $outArray->Columns = $columnsOut;
        }

        return json_encode($outArray);
    }

    /**
     * @Override
     */
    public function setJson(string $json, bool $runValidate)
    {
        $obj = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new \Exception('Invalid JSON');
        }

        if (isset($obj->General))
        {
            $this->General = JsonHelper::jsonToObject(json_encode($obj->General), General::class);
        }
        if (isset($obj->Header))
        {
            $this->Header = JsonHelper::jsonToObject(json_encode($obj->Header), Header::class);
        }
        if (isset($obj->Block))
        {
            $this->Block = JsonHelper::jsonToObject(json_encode($obj->Block), Block::class);
        }
        if (isset($obj->Columns))
        {
            $this->Columns = new Columns();
            foreach ($obj->Columns as $column)
            {
                $this->Columns->add(JsonHelper::jsonToObject(json_encode($column), Column::class));
            }
        }
        
        if ($runValidate && !$this->validate())
        {
            throw new \Exception('This Entity formation is not valid');
        }

        return $this;
    }
}