# PHP Complex CSV parser
Parse complex CSV to JSON

[![Build Status](https://travis-ci.org/robsonala/php-complex-parser.svg?branch=master)](https://travis-ci.org/robsonala/php-complex-parser)

***

## Simple usage

```php
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
/*
$out =>
array(
    array(
        'partcode' => 'CN1339361',
        'sales' => array(1,7,1,2,4),
        'stock' => array(1,7,1,2),
        'fcast' => array(0,3,3)
    ),
    array(
        'partcode' => 'CN1339987',
        'sales' => array(2,6,0,2,4),
        'stock' => array(2,6,0,2),
        'fcast' => array(2,2,4)
    )
)
*/
```

***
## Settings Options

### General
| Name  | Description | Type |
| --- | --- | --- |
| IgnoreLinesBegin  | Number of lines to be ignored at begin of the file  | Integer |

### Header
| Name  | Description | Type |
| --- | --- | --- |
| Global  | Where header line is located (TRUE = Global / FALSE = Block)  | Boolean |
| Position  | Position of the header line  | PositionHeader |
### PositionHeader
| Name  | Description | Type |
| --- | --- | --- |
| Line | Line where header is placed | Integer |

### Block
| Name  | Description | Type |
| --- | --- | --- |
| Transpose  | Transpose the block before process it  | Boolean |
| Size  | If the blocks are fixed size you can split it by the number of lines  | Integer |
| Break  | If it's not fixed size you can select the type of block delimiter  | Enum BlockBreak |
| BreakSearch | Value to be find in specific BlockBreaks | String/Regex |
| BreakSearchColumn | Column position for BlockSearch | Integer |

### Column
| Name  | Description | Type |
| --- | --- | --- |
| Type  | Type of parser  | Enum ColumnType |
| Name | Name to be returned on out data | String |
| KeepHeader | Show the original header on out information | Boolean|
| Position | Position where the data will be extracted | PositionColumn |

### PositionColumn
| Name  | Description | Type |
| --- | --- | --- |
| Line | Line where data will be extracted | Integer |
| Column | Column where the data will be extracted (for Single extractor) | Integer |
| Search | Information to be search and select the line (for Multiple extractor) | String/Search |
| SearchColumn | Column position for 'Search' | Integer |
| HeaderMatch | Using header to match the columns (for Multiple extractor) | String/Search |
| Range | Select data from a list of columns (for Multiple extractor) | Array(integer, integer/null) |

***
## Enumerators

### BlockBreak
| ID | Name | Description |
| --- | --- | --- |
| 1 | EmptyLine | Break block each empty line |
| 2 | MatchLastLine | Break block every time the extractor find a specific last line |
| 2 | MatchFirstLine | Break block every time the extractor find a specific first line |

### ColumnType
| ID | Name | Description |
| --- | --- | --- |
| 1 | Single | Search just single information from column/line |
| 1 | Multiple | Search multiple information from line |