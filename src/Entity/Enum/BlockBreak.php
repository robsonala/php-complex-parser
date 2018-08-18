<?php
namespace PHPComplexParser\Entity\Enum;

class BlockBreak extends \MyCLabs\Enum\Enum
{
    private const EmptyLine = 1;
    private const MatchLastLine = 2;
    private const MatchFirstLine = 3;
}