<?php
namespace PHPComplexParser\Component;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class JsonHelper
{

    public static function objectToJson(object $obj)
    {
        //$serializer = new Serializer([new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())], [new JsonEncoder()]);
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return $serializer->serialize($obj, 'json');
    }

    public static function jsonToObject(string $value, string $className)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        return $serializer->deserialize($value, $className, 'json');
    }

}