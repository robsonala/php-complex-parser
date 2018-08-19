<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Component\JsonHelper;

abstract class BaseEntity implements IEntity
{
    public function validate()
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getJson(bool $runValidate)
    {
        if ($runValidate && !$this->validate())
        {
            throw new \Exception('This Entity formation is not valid');
        }
        
        return JsonHelper::objectToJson($this);
    }

    public function setJson(string $json, bool $runValidate)
    {
        $obj = JsonHelper::jsonToObject($json, get_class($this));
        $reflection = new \ReflectionClass($obj);

        foreach ($reflection->getProperties() as $property)
        {
            $property->setAccessible(true);
            $this->{$property->getName()} = $property->getValue($obj);
        }
        
        if ($runValidate && !$this->validate())
        {
            throw new \Exception('This Entity formation is not valid');
        }

        return $this;
    }
}