<?php
namespace PHPComplexParser\Entity;

class General extends BaseEntity
{
    /**
     * Number of lines must be ignored at beginning of the files
     * 
     * @var int
     */
    protected $IgnoreLinesBegin;

    public function setIgnoreLinesBegin(int $value)
    {
        if ($value < 0)
        {
            throw new \Exception('Invalid IgnoreLinesBegin');
        }

        $this->IgnoreLinesBegin = $value;

        return $this;
    }

    public function getIgnoreLinesBegin()
    {
        return $this->IgnoreLinesBegin;
    }

    public function validate()
    {
        if (!isset($this->IgnoreLinesBegin))
        {
            return false;
        }

        return true;
    }
}