<?php
namespace PHPComplexParser\Entity;

use PHPComplexParser\Component\JsonHelper;

abstract class BaseEntity
{
    public function validate()
    {
        throw new \RuntimeException("Not implemented");
    }

    public function getJson($_null)
    {
        if (!$this->validate())
        {
            throw new \Exception('This Entity is not valid');
        }
        
        return JsonHelper::objectToJson($this);
    }

    public function setJson(string $json)
    {
        $obj = JsonHelper::jsonToObject($json, get_class($this));
        $reflection = new \ReflectionClass($obj);

        foreach ($reflection->getProperties() as $property)
        {
            $property->setAccessible(true);
            $this->{$property->getName()} = $property->getValue($obj);
        }
        
        if (!$this->validate())
        {
            throw new \Exception('This Entity is not valid');
        }

        return $this;
    }
}