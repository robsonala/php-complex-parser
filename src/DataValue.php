<?php
namespace PHPComplexParser;

class DataValue
{
    /**
     * Array data
     * 
     * @var array
     */
    protected $Data;

    public function setData(array $data) : bool
    {
        $this->Data = $data;

        return true;
    }

    public function getData() : array
    {
        return $this->Data;
    }

    public function splice(int $offset, int $length = null)
    {
        if ($length == null)
        {
            $length = count($this->Data);
        }
        
        return array_splice($this->Data, $offset, $length);
    }
}