<?php
namespace PHPComplexParser\Component;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CustomObjectNormalizer extends ObjectNormalizer
{
    public function normalize($object, $format = null, array $context = [])
    {
        $data = parent::normalize($object, $format, $context);

        return array_filter($data, function ($value) {
            return null !== $value;
        });
    }
}