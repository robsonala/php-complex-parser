<?php

/**
 * Simple example using CSV by String and JSON settings
 */

require ("vendor/autoload.php");

use PHPComplexParser\Entity\Enum\ColumnType;
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

$settings = json_encode([
    'Header' => [
        'Global' => false,
        'Position' => [
            'Line' => 1
        ]
    ],
    'Block' => [
        'Transpose' => false,
        'Size' => 6
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
            'KeepHeader' => false,
            'Position' => [
                'Line' => 3,
                'Range' => [1, null] 
            ]
        ],
        [
            'Type' => ColumnType::Multiple()->getValue(),
            'Name' => 'stock',
            'KeepHeader' => false,
            'Position' => [
                'Search' => 'Stock',
                'SearchColumn' => 0,
                'Range' => [1, 4] 
            ]
        ],
        [
            'Type' => ColumnType::Multiple()->getValue(),
            'Name' => 'fcast',
            'KeepHeader' => false,
            'Position' => [
                'Search' => 'Forecast',
                'SearchColumn' => 0,
                'HeaderMatch' => 'Week [0-9]{1,2} 2018' 
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

            [fcast] => Array
                (
                    [0] => 0
                    [1] => 3
                    [2] => 3
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

            [fcast] => Array
                (
                    [0] => 2
                    [1] => 2
                    [2] => 4
                )

        )

)

*/