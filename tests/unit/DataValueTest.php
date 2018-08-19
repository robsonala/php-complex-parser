<?php

use PHPComplexParser\DataValue;

class DataValueTest extends \Codeception\Test\Unit
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

    public function testSetData()
    {
        $data = [
            ['lorem', 'ipsum', 'dolor'],
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new DataValue();

        $this->tester->assertTrue($obj->setData($data));
    }

    public function testGetData()
    {
        $data = [
            ['lorem', 'ipsum', 'dolor'],
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new DataValue();
        $obj->setData($data);

        $this->tester->assertEquals($data, $obj->getData());
    }

    public function testSplice()
    {
        $data = [
            [rand(100,999), rand(100,999), rand(100,999)],
            [rand(100,999), rand(100,999), rand(100,999)]
        ];

        $obj = new DataValue();
        $obj->setData(array_merge([['lorem', 'ipsum', 'dolor']], $data));
        $obj->splice(0,1);

        $this->tester->assertEquals($data, $obj->getData());

    }
}