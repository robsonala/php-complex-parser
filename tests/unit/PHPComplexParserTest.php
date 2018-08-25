<?php

use PHPComplexParser\PHPComplexParser;
use PHPComplexParser\Entity\{BaseEntity, Block, Column, General, Header, PositionColumn, PositionHeader, Settings};
use PHPComplexParser\Entity\Enum\{BlockBreak, ColumnType};
use PHPComplexParser\Repository\Columns;

class PHPComplexParserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function genSettings()
    {
        // HEADER
        $pos = new PositionHeader();
        $pos->setLine(rand(1,9));

        $header = new Header();
        $header->setGlobal(true);
        $header->setPosition($pos);

        // BLOCK
        $block = new Block();
        $block->setBreak(BlockBreak::EmptyLine()->getValue());

        // COLUMN
        $pos = new PositionColumn();
        $pos->setLine(rand(1,9));
        $pos->setColumn(rand(1,9));

        $column = new Column();
        $column->setType(ColumnType::Single()->getValue());
        $column->setPosition($pos);

        // COLUMNS
        $columns = new Columns();
        $columns->add($column);

        // SETTINGS
        $settings = new Settings();
        $settings->setHeader($header);
        $settings->setBlock($block);
        $settings->setColumns($columns);

        return $settings;
    }

    private function genAlleatoryBlock()
    {
        return [
            ['START_' . uniqid()],
            ['Stock', rand(100,999),rand(100,999),rand(100,999)],
            ['Sales', rand(100,999),rand(100,999),rand(100,999)],
            ['END_' . uniqid()]
        ];
    }

    /**
     * @expectedException \Exception
     */
    public function testLoadSettingsJsonInvalid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertFalse($obj->loadSettingsJson('{}'));
    }

    public function testLoadSettingsJsonValid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertTrue($obj->loadSettingsJson($this->genSettings()->getJson(true)));
    }

    public function testLoadSettingsValid()
    {
        $obj = new PHPComplexParser();

        $this->tester->assertTrue($obj->loadSettings($this->genSettings()));
    }

    public function testLoadArray()
    {
        $data = [
            $this->genAlleatoryBlock(),
            $this->genAlleatoryBlock(),
            $this->genAlleatoryBlock(),
            $this->genAlleatoryBlock()
        ];

        $obj = new PHPComplexParser();
        $this->tester->assertTrue($obj->loadArray($data));
    }

    public function testLoadCsvStr()
    {
        $str = 'lorem,ipsum,dolor,sit' . chr(10);
        $str.= '1,2,3,4' . chr(10);
        $str.= '5,6,7,8' . chr(10);

        $obj = new PHPComplexParser();
        $this->tester->assertTrue($obj->loadCsvStr($str));
    }

    public function testProcessData1()
    {
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

        $expected = [
            [
                'partcode' => 'CN1339361',
                'sales' => [1,7,1,2,4],
                'stock' => [1,7,1,2],
                'fcast' => [0,3,3]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [2,6,0,2,4],
                'stock' => [2,6,0,2],
                'fcast' => [2,2,4]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData2()
    {
        $str = 'CN1339361,,,,,,,
        ,week -3,week -2,week -1,current week,week +1,week +2,week +3
        Sales,1,5,6,9,5,1,8
        Stock,1,7,1,2,9,5,
        Forecast,3,7,0,3,7,0,21
        ,,,,,,,
        CN1339987,,,,,,,
        ,week -3,week -2,week -1,current week,week +1,week +2,week +3
        Sales,6,7,1,3,9,2,7
        Stock,2,6,0,2,,3,2
        Forecast,5,0,2,2,4,1,5';

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
                    'KeepHeader' => true,
                    'Position' => [
                        'Search' => 'Sales',
                        'Search_Column' => 0,
                        'HeaderMatch' => '(current week|week \+[0-9]{1,2})' 
                    ]
                ]
            ]
        ]);

        $expected = [
            [
                'partcode' => 'CN1339361',
                'sales' => [
                    'current week' => 9,
                    'week +1' => 5,
                    'week +2' => 1,
                    'week +3' => 8
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'current week' => 3,
                    'week +1' => 9,
                    'week +2' => 2,
                    'week +3' => 7
                ]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData3()
    {
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
                        'Range' => [1, null]
                    ]
                ]
            ]
        ]);

        $expected = [
            [
                'partcode' => 'CN1339361',
                'sales' => [
                    'week -3' => 1,
                    'week -2' => 5,
                    'week -1' => 6,
                    'current week' => 9,
                    'week +1' => 5,
                    'week +2' => 1,
                    'week +3' => 8
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'week -3' => 6,
                    'week -2' => 7,
                    'week -1' => 1,
                    'current week' => 3,
                    'week +1' => 9,
                    'week +2' => 2,
                    'week +3' => 7
                ]
            ],
            [
                'partcode' => 'CN922887',
                'sales' => [
                    'week -3' => 5,
                    'week -2' => 0,
                    'week -1' => 2,
                    'current week' => 2,
                    'week +1' => 4,
                    'week +2' => 1,
                    'week +3' => 5
                ]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData4()
    {
        $str = 'CN1339361,,,,,
        ,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
        Sales,1,5,6,9,1
        Stock,1,7,1,2,4
        Total,0,0,0,0,0
        CN1339987,,,,,
        ,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
        Sales,6,7,1,3,7
        Stock,2,6,0,2,4
        Forecast,5,0,2,2,4
        Total,0,0,0,0,0
        CN1339987,,,,,
        ,Week 51 2017,Week 52 2017,Week 01 2018,Week 02 2018,Week 03 2018
        Sales,6,7,1,3,7
        Total,0,0,0,0,0';

        $settings = json_encode([
            'Header' => [
                'Global' => false,
                'Position' => [
                    'Line' => 1
                ]
            ],
            'Block' => [
                'Break' => BlockBreak::MatchLastLine()->getValue(),
                'Transpose' => false,
                'BreakSearch' => 'Total',
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

        $expected = [
            [
                'partcode' => 'CN1339361',
                'sales' => [
                    'Week 51 2017' => 1,
                    'Week 52 2017' => 5,
                    'Week 01 2018' => 6,
                    'Week 02 2018' => 9,
                    'Week 03 2018' => 1
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'Week 51 2017' => 6,
                    'Week 52 2017' => 7,
                    'Week 01 2018' => 1,
                    'Week 02 2018' => 3,
                    'Week 03 2018' => 7
                ],
                'forecast' => [
                    'Week 51 2017' => 5,
                    'Week 52 2017' => 0,
                    'Week 01 2018' => 2,
                    'Week 02 2018' => 2,
                    'Week 03 2018' => 4
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'Week 51 2017' => 6,
                    'Week 52 2017' => 7,
                    'Week 01 2018' => 1,
                    'Week 02 2018' => 3,
                    'Week 03 2018' => 7
                ]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData5()
    {
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

        $expected = [
            [
                'partcode' => 'CN1339361',
                'sales' => [
                    'Week 51 2017' => 1,
                    'Week 52 2017' => 5,
                    'Week 01 2018' => 6,
                    'Week 02 2018' => 9,
                    'Week 03 2018' => 1
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'Week 51 2017' => 6,
                    'Week 52 2017' => 7,
                    'Week 01 2018' => 1,
                    'Week 02 2018' => 3,
                    'Week 03 2018' => 7
                ],
                'forecast' => [
                    'Week 51 2017' => 5,
                    'Week 52 2017' => 0,
                    'Week 01 2018' => 2,
                    'Week 02 2018' => 2,
                    'Week 03 2018' => 4
                ]
            ],
            [
                'partcode' => 'CN1339987',
                'sales' => [
                    'Week 51 2017' => 6,
                    'Week 52 2017' => 7,
                    'Week 01 2018' => 1,
                    'Week 02 2018' => 3,
                    'Week 03 2018' => 7
                ]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData6()
    {
        $str = ',w1,w2,w3
        
        CN123
        lorem,1,2,3
        ipsum,3,2,1
        CN321
        lorem,1,2,3
        ipsum,3,2,1';

        $settings = json_encode([
            'General' => [
                'IgnoreLinesBegin' => 2,
            ],
            'Header' => [
                'Global' => true,
                'Position' => [
                    'Line' => 0
                ]
            ],
            'Block' => [
                'Size' => 3
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
                    'Name' => 'lorem',
                    'Position' => [
                        'Line' => 1,
                        'Range' => [1, null]
                    ]
                    ],
                [
                    'Type' => ColumnType::Multiple()->getValue(),
                    'Name' => 'ipsum',
                    'KeepHeader' => true,
                    'Position' => [
                        'Line' => 2,
                        'Range' => [1, null]
                    ]
                ]
            ]
        ]);

        $expected = [
            [
                'partcode' => 'CN123',
                'lorem' => [1,2,3],
                'ipsum' => ['w1' => 3, 'w2' => 2, 'w3' => 1]
            ],
            [
                'partcode' => 'CN321',
                'lorem' => [1,2,3],
                'ipsum' => ['w1' => 3, 'w2' => 2, 'w3' => 1]
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }

    public function testProcessData7()
    {
        $str = 'abc,a
        w1,1
        w2,1
        def,a
        w1,2
        w2,2';

        $settings = json_encode([
            'Header' => [
                'Global' => false,
                'Position' => [
                    'Line' => 0
                ]
            ],
            'Block' => [
                'Size' => 3,
                'Transpose' => true
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
                    'Name' => 'a',
                    'Position' => [
                        'Line' => 1,
                        'Range' => [1, null]
                    ]
                ]
            ]
        ]);

        $expected = [
            [
                'partcode' => 'abc',
                'a' => [1,1],
            ],
            [
                'partcode' => 'def',
                'a' => [2,2],
            ]
        ];

        // Load CSV
        $parser = new PHPComplexParser();
        $parser->loadCsvStr($str);

        // Load Settings
        $parser->loadSettingsJson($settings);

        $this->tester->assertEquals($expected, $parser->processData());
    }
}