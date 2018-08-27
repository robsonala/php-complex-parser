<?php

require ("vendor/autoload.php");

use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;
use PHPComplexParser\PHPComplexParser;
$str = 'CN1339361,,,,,
,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
Sales,1,5,6,9,1
Stock,1,7,1,2,4
CN1339987,,,,,
,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
Sales,6,7,1,3,7
Stock,2,6,0,2,4
Forecast,5,0,2,2,4
CN1339987,,,,,
,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
Sales,6,7,1,3,7';

$settings = json_encode([
    'Header' => [
        'Global' => false,
        'Position' => [
            'Line' => 1
        ]
    ],
    'Block' => [
        'Break' => BlockBreak::MatchFirstLine()->getValue(),
        'Transpose' => false,
        'BreakSearch' => 'CN\d+',
        'BreakSearchColumn' => 0
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
                'Range' => [1, null]
            ]
        ],
        [
            'Type' => ColumnType::Multiple()->getValue(),
            'Name' => 'forecast',
            'KeepHeader' => true,
            'Position' => [
                'Search' => 'Forecast',
                'Search_Column' => 0,
                'Range' => [1, null]
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
                    [Week 51 2017] => 1
                    [Week 52 2017] => 5
                    [Week 01 2018] => 6
                    [Week 02 2018] => 9
                    [Week 03 2018] => 1
                )

        )

    [1] => Array
        (
            [partcode] => CN1339987
            [sales] => Array
                (
                    [Week 51 2017] => 6
                    [Week 52 2017] => 7
                    [Week 01 2018] => 1
                    [Week 02 2018] => 3
                    [Week 03 2018] => 7
                )

            [forecast] => Array
                (
                    [Week 51 2017] => 5
                    [Week 52 2017] => 0
                    [Week 01 2018] => 2
                    [Week 02 2018] => 2
                    [Week 03 2018] => 4
                )

        )

    [2] => Array
        (
            [partcode] => CN1339987
            [sales] => Array
                (
                    [Week 51 2017] => 6
                    [Week 52 2017] => 7
                    [Week 01 2018] => 1
                    [Week 02 2018] => 3
                    [Week 03 2018] => 7
                )

        )

)

*/