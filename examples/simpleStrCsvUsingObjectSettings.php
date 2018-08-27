<?php

/**
 * Simple example using CSV by String and Class settings
 */

require ("vendor/autoload.php");

use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;
use PHPComplexParser\PHPComplexParser;

$str = 'CN1339361,,,,,
,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
Sales,1,5,6,9,1
Stock,1,7,1,2,4
Forecast,3,7,0,3,3
,,,,,
CN1339987,,,,,
,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
Sales,6,7,1,3,7
Stock,2,6,0,2,4
Forecast,5,0,2,2,4';

$pos = new PositionHeader();
$pos->setLine(1);

$header = new Header();
$header->setGlobal(false);
$header->setPosition($pos);

$block = new Block();
$block->setTranspose(false);
$block->setSize(6);

$cols = new Columns();

$pos = new PositionColumn();
$pos->setLine(0);
$pos->setColumn(0);

$col = new Column();
$col->setType(ColumnType::Single()->getValue());
$col->setName('partcode');
$col->setPosition($pos);
$cols->add($col);

$pos = new PositionColumn();
$pos->setLine(3);
$pos->setRange(1, null);

$col = new Column();
$col->setType(ColumnType::Multiple()->getValue());
$col->setName('sales');
$col->setKeepHeader(false);
$col->setPosition($pos);
$cols->add($col);

$pos = new PositionColumn();
$pos->setSearch('Stock');
$pos->setSearchColumn(0);
$pos->setRange(1, 4);

$col = new Column();
$col->setType(ColumnType::Multiple()->getValue());
$col->setName('stock');
$col->setKeepHeader(false);
$col->setPosition($pos);
$cols->add($col);

$settings = new Settings();
$settings->setHeader($header);
$settings->setBlock($block);
$settings->setColumns($cols);

// Load CSV
$parser = new PHPComplexParser();
$parser->loadCsvStr($str);

// Load Settings
$parser->loadSettings($settings);

$out = $parser->processData();

echo '<pre>' . print_r($out , true) . chr(10);

/*
RESULT:

Array
(
    [0] => Array
        (
            [partcode] => CN1339361
            [sales] => Array
                (
                    [0] => 1
                    [1] => 7
                    [2] => 1
                    [3] => 2
                    [4] => 4
                )

            [stock] => Array
                (
                    [0] => 1
                    [1] => 7
                    [2] => 1
                    [3] => 2
                )

        )

    [1] => Array
        (
            [partcode] => CN1339987
            [sales] => Array
                (
                    [0] => 2
                    [1] => 6
                    [2] => 0
                    [3] => 2
                    [4] => 4
                )

            [stock] => Array
                (
                    [0] => 2
                    [1] => 6
                    [2] => 0
                    [3] => 2
                )

        )

)

*/