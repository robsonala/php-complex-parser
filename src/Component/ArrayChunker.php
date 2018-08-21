<?php
namespace PHPComplexParser\Component;

class ArrayChunker
{
    public static function bySize(int $size, array &$data) : array
    {
        return array_chunk($data, $size);
    }

    public static function byEmptyLine(array &$data) : array
    {
        $chunk = [];
        foreach ($data as $line)
        {
            if (!implode('', $line))
            {
                $chunks[] = $chunk;
                $chunk = [];

                continue;
            }

            $chunk[] = $line;
        }
        
        if (count($chunk) > 0)
        {
            $chunks[] = $chunk;
        }

        return $chunks;
    }

    public static function byMatchFirstLine(string $search, ?int $columnPosition, array &$data) : array
    {    
        $chunk = [];
        foreach ($data as $line)
        {
            if ($columnPosition)
            {
                if (!isset($line[$columnPosition])) // Ignore line if there are no position to find
                {
                    $chunk[] = $line;
                    continue;
                }

                $searchPosition = $line[$columnPosition];
            } else {
                $searchPosition = implode('', $line);
            }

            if (preg_match(sprintf('/%s/', $search), $searchPosition))
            {
                if (count($chunk) > 0)
                {
                    $chunks[] = $chunk;
                    $chunk = []; 
                }
            }
            
            $chunk[] = $line;
        }
        
        if (count($chunk) > 0)
        {
            $chunks[] = $chunk;
        }

        return $chunks;
    }

    public static function byMatchLastLine(string $search, ?int $columnPosition, array &$data) : array
    {    
        $chunk = [];
        foreach ($data as $line)
        {
            $chunk[] = $line;

            if ($columnPosition)
            {
                if (!isset($line[$columnPosition])) // Ignore line if there are no position to find
                {
                    continue;
                }

                $searchPosition = $line[$columnPosition];
            } else {
                $searchPosition = implode('', $line);
            }
            
            if (preg_match(sprintf('/%s/', $search), $searchPosition))
            {
                $chunks[] = $chunk;
                $chunk = [];
            }
        }
        
        if (count($chunk) > 0)
        {
            $chunks[] = $chunk;
        }

        return $chunks;
    }
}