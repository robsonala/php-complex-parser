<?php
namespace PHPComplexParser\Component;

class ArrayHelper
{
    public static function transpose(array $data) : array
    {
        return array_map(null, ...$data);
    }
}