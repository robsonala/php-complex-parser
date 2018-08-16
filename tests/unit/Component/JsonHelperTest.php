<?php
namespace Component;

use PHPComplexParser\Component\JsonHelper;

class MyTest
{
    public $LoremIpsum;
    public $DolorSit;

    public function __construct()
    {
        $this->LoremIpsum = uniqid();
        $this->DolorSit = uniqid();
    }
}

class JsonHelperTest extends \Codeception\Test\Unit
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

    public function testObjectToJson()
    {   
        $obj = new MyTest();
        $json = json_encode((object)['LoremIpsum' => $obj->LoremIpsum, 'DolorSit' => $obj->DolorSit]);

        $this->tester->assertEquals($json, JsonHelper::objectToJson($obj));
    }

    public function testJsonToObject()
    {
        $obj = new MyTest();
        $json = json_encode((object)['LoremIpsum' => $obj->LoremIpsum, 'DolorSit' => $obj->DolorSit]);

        $this->tester->assertEquals($obj, JsonHelper::jsonToObject($json, MyTest::class));
    }
}