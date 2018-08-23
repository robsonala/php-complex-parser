<?php
namespace PHPComplexParser;

use PHPComplexParser\Entity\Settings;

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
}