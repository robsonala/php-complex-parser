<?php
namespace PHPComplexParser;

use PHPComplexParser\Entity\Enum\ColumnType;
use PHPComplexParser\Entity\{Column, PositionColumn};

class Extractor
{
    public static function getDataFromLine(int $lineNumber, array $line, Column $settings, array $header = null) : ?array
    {
        if (!$settings->validate())
        {
            throw new \Exception('Invalid Column entity');
        }   

        switch (new ColumnType($settings->getType()))
        {
            case ColumnType::Single():
                if ($settings->getPosition()->getLine() === $lineNumber)
                {
                    return self::single($line, $settings);
                }
            case ColumnType::Multiple():
                if (
                    ($settings->getPosition()->getLine() === $lineNumber) || 
                    (
                        $settings->getPosition()->getSearch() &&
                        $settings->getPosition()->getSearchColumn() >= 0 &&
                        preg_match(sprintf('/%s/', $settings->getPosition()->getSearch()), $line[$settings->getPosition()->getSearchColumn()])
                    )
                )
                {
                    if ($settings->getPosition()->getRange())
                    {
                        return self::multipleRange($line, $settings, $header ?? null);
                    } else if ($settings->getPosition()->getHeaderMatch()) {
                        return self::multipleHeaderMatch($line, $settings, $header);
                    }
                }
                break;
        }

        return null;
    }

    // TODO: Make it private and change unit test
    public static function single(array $line, Column $settings) : array
    {
        return [$settings->getName() => $line[$settings->getPosition()->getColumn()]];
    }

    // TODO: Make it private and change unit test
    public static function multipleRange(array $line, Column $settings, array $header = null) : array
    {
        $range = $settings->getPosition()->getRange();

        $array_slice = array_slice($line, $range[0], $range[1] === null ? count($line) : $range[1], true);
                            
        if ($settings->isKeepHeader() === true)
        {
            $data = [];
            foreach ($array_slice as $key => $value)
            {
                $data[$header[$key]] = $value;
            }

            return [$settings->getName() => $data];
        } else {
            return [$settings->getName() => array_values($array_slice)];
        }
    }

    // TODO: Make it private and change unit test
    public static function multipleHeaderMatch(array $line, Column $settings, array $header) : ?array
    {
        if (preg_match(sprintf('/%s/', $settings->getPosition()->getSearch()), $line[$settings->getPosition()->getSearchColumn()]))
        {
            $data = [];
            foreach ($header as $key => $value)
            {
                if (preg_match(sprintf('/%s/',$settings->getPosition()->getHeaderMatch()),$value))
                {
                    if ($settings->isKeepHeader() === true)
                    {
                        $data[$header[$key]] = $line[$key];
                    } else {
                        $data[] = $line[$key];
                    }
                }
            }
    
            return [$settings->getName() => $data];
        }

        return null;
    }
}