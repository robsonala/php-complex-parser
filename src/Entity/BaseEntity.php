<?php
namespace PHPComplexParser\Entity;

abstract class BaseEntity
{
    public function validate()
    {
        throw new \RuntimeException("Not implemented");
    }
}