<?php
namespace PHPComplexParser\Repository;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\Column;

class Columns implements IRepository
{
    /**
     * @var Array of Column
     */
    protected $Columns;

    public function __construct()
    {
        $this->Columns = [];
    }

    public function getAll() : array
    {
        return $this->Columns;
    }

    public function find($searchTerm) : ?BaseEntity
    {
        foreach ($this->Columns as $column)
        {
            if ($column->getName() === $searchTerm)
            {
                return $column;
            }
        }

        return null;
    }

    public function add(BaseEntity $item) : bool
    {
        if (!($item instanceof Column) || !$item->validate())
        {
            return false;
        }

        $this->Columns[] = $item;

        return true;
    }

    public function exists($searchTerm) : bool
    {
        return !!$this->find($searchTerm);
    }

    public function remove($searchTerm) : bool
    {
        $newArray = [];

        foreach ($this->Columns as $column)
        {
            if ($column->getName() === $searchTerm);
            else {
                $newArray[] = $column;
            }
        }

        if ($newArray != $this->Columns)
        {
            $this->Columns = $newArray;
            return true;
        }

        return false;
    }
}