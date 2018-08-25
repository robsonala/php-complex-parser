<?php
namespace PHPComplexParser;

use PHPComplexParser\Entity\Settings;
use PHPComplexParser\Component\ArrayHelper;

/**
* PHP Complex Parser for CSV
*
* @author   Robson Alviani <robson.alviani@gmail.com>
* @version  1.0
*/
class PHPComplexParser
{
    /**
     * Parser Settings
     * 
     * @var Settings
     */
    protected $Settings;

    /**
     * Data
     * @var ArrayData
     */
    protected $Data;

    public function loadSettingsJson(string $json)
    {
        $this->Settings = new Settings();
        $this->Settings->setJson($json, true);

        return true;
    }

    public function loadSettings(Settings $obj)
    {
        $this->Settings = $obj;

        return true;
    }

    public function loadArray(array $data)
    {
        $this->Data = new ArrayData();
        return $this->Data->setData($data);
    }

    public function loadCsvStr(string $str)
    {
        $csv = new \Jabran\CSV_Parser();
        $csv->fromString($str);
        
        return $this->loadArray($csv->parse(false));
    }

    public function processData()
    {
        if (!isset($this->Settings))
        {
            throw new \Exception('Settings not setup');
        }

        if (!isset($this->Data))
        {
            throw new \Exception('Data not imported');
        }

        if ($this->Settings->getHeader()->isGlobal() === true)
        {
            $headerLineData = $this->Data->getHeader($this->Settings->getHeader());
        }

        if ($this->Settings->getGeneral() && $this->Settings->getGeneral()->getIgnoreLinesBegin() > 0)
        {
            $this->Data->splice(0,$this->Settings->getGeneral()->getIgnoreLinesBegin());
        }

        $this->Data->createChunks($this->Settings->getBlock());
        $this->Data->unsetData();

        $outData = [];
        foreach ($this->Data->getChunks() as $blockIndex => $blockData)
        {
            if ($this->Settings->getBlock()->isTranspose() === true)
            {
                $blockData = ArrayHelper::transpose($blockData);
            }

            if ($this->Settings->getHeader()->isGlobal() === false)
            {
                $headerLineData = $this->Data->getHeader($this->Settings->getHeader(), $blockIndex);
            }

            $outBlockData = [];
            foreach ($blockData as $lineIndex => $lineData)
            {
                foreach ($this->Settings->getColumns()->getAll() as $columnSettings)
                {
                    $info = Extractor::getDataFromLine($lineIndex, $lineData, $columnSettings, $headerLineData);
                    
                    if ($info)
                    {
                        $outBlockData = array_merge($outBlockData, $info);
                    }
                }
            }

            if ($outBlockData)
            {
                $outData[] = $outBlockData;
            }
        }

        return $outData;
    }
}