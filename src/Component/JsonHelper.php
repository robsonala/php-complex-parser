<?php
namespace PHPComplexParser\Component;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use PHPComplexParser\Component\CustomObjectNormalizer;

class JsonHelper
{
    public static function objectToJson($obj)
    {
        if (!is_object($obj))
        {
            throw new \TypeError('Invalid obj params');
        }

        $serializer = new Serializer([new CustomObjectNormalizer()], [new JsonEncoder()]);

        return $serializer->serialize($obj, 'json');
    }

    public static function jsonToObject(string $value, string $className)
    {
        $serializer = new Serializer([new CustomObjectNormalizer()], [new JsonEncoder()]);

        return $serializer->deserialize($value, $className, 'json');
    }
}