<?php

/**
 * Simple example using Array data and JSON settings
 */

require ("vendor/autoload.php");

use PHPComplexParser\Entity\Enum\ColumnType;
use PHPComplexParser\PHPComplexParser;

$data = [
    ['CN1339361'],
    ['','Week 51 2017','Week 52 2017','Week 01 2018','Week 02 2018','Week 03 2018'],
    ['Sales',1,5,6,9,1],
    ['Stock',1,7,1,2,4],
    ['Forecast',3,7,0,3,3],
    ['',''],
    ['CN1399611'],
    ['','Week 51 2017','Week 52 2017','Week 01 2018','Week 02 2018','Week 03 2018'],
    ['Sales',5,5,9,9,7],
    ['Stock',1,1,2,2,4],
    ['Forecast',7,7,2,3,1],
    ['','']
];

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
$parser->loadArray($data);

// Load Settings
$parser->loadSettingsJson($settings);

$out = $parser->processData();

echo '<pre>' . print_r($out , true) . chr(10);