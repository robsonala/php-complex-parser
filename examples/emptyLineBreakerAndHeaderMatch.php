<?php

require ("vendor/autoload.php");

use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;
use PHPComplexParser\PHPComplexParser;

$str='CN1339361,,,,,,,
,week -3,week -2,week -1,current week,week +1,week +2,week +3
Sales,1,5,6,9,5,1,8
Stock,1,7,1,2,9,5,
,,,,,,,
CN1339987,,,,,,,
,week -3,week -2,week -1,current week,week +1,week +2,week +3
Sales,6,7,1,3,9,2,7
Stock,2,6,0,2,,3,2
Forecast,5,0,2,2,4,1,5
,,,,,,,
CN922887,,,,,,,
,week -3,week -2,week -1,current week,week +1,week +2,week +3
Sales,5,0,2,2,4,1,5';

$settings = json_encode([
    'Header' => [
        'Global' => false,
        'Position' => [
            'Line' => 1
        ]
    ],
    'Block' => [
        'Break' => BlockBreak::EmptyLine()->getValue(),
        'Transpose' => false,
    ],
    'Columns' => [
        [
            'Type' => ColumnType::Single()->getValue(),
            'Name' => 'partcode',
            'Position' => [
                'Line' => 0,
                'Column' => 0
            ]
        ],
        [
            'Type' => ColumnType::Multiple()->getValue(),
            'Name' => 'sales',
            'KeepHeader' => true,
            'Position' => [
                'Search' => 'Sales',
                'Search_Column' => 0,
                'HeaderMatch' => '(current week|week \+[0-9]{1,2})' 
            ]
        ]
    ]
]);

// Load CSV
$parser = new PHPComplexParser();
$parser->loadCsvStr($str);

// Load Settings
$parser->loadSettingsJson($settings);

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
                    [current week] => 9
                    [week +1] => 5
                    [week +2] => 1
                    [week +3] => 8
                )

        )

    [1] => Array
        (
            [partcode] => CN1339987
            [sales] => Array
                (
                    [current week] => 3
                    [week +1] => 9
                    [week +2] => 2
                    [week +3] => 7
                )

        )

    [2] => Array
        (
            [partcode] => CN922887
            [sales] => Array
                (
                    [current week] => 2
                    [week +1] => 4
                    [week +2] => 1
                    [week +3] => 5
                )

        )

)

*/