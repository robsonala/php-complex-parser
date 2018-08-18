<?php
namespace PHPComplexParser\Entity;

class PositionColumns extends Position
{
    /**
     * Column Position
     * 
     * @var int
     */
    protected $Column;

    /**
     * String|Regex to be search
     * 
     * @var string
     */
    protected $Search;

    /**
     * Column position to search term $Search
     * 
     * @var int
     */
    protected $SearchColumn;

    /**
     * String|Regex Header of column with the values
     * 
     * @var string
     */
    protected $HeaderMatch;

    /**
     * Start/End position of columns to find data
     * 
     * @var array
     */
    protected $Range;
    
    public function setColumn(int $value)
    {
        if ($value < 0)
        {
            throw new \Exception('Invalid Column');
        }
        
        $this->Column = $value;
    }

    public function getColumn()
    {
        return $this->Column;
    }

    public function setSearch(string $value)
    {
        $this->Search = $value;
    }

    public function getSearch()
    {
        return $this->Search;
    }
    
    public function setSearchColumn(int $value)
    {
        if ($value < 0)
        {
            throw new \Exception('Invalid SearchColumn');
        }
        
        $this->SearchColumn = $value;
    }

    public function getSearchColumn()
    {
        return $this->SearchColumn;
    }

    public function setHeaderMatch(string $value)
    {
        $this->HeaderMatch = $value;
    }

    public function getHeaderMatch()
    {
        return $this->HeaderMatch;
    }

    public function setRange(int $start, int $end = null)
    {
        $this->Range = [$start, $end];
    }

    public function getRange()
    {
        return $this->Range;
    }
}