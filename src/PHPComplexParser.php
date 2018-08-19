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
}