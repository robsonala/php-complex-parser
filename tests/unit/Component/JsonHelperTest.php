<?php
namespace Component;

use PHPComplexParser\Component\JsonHelper;

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
        $json = json_encode((object)['lorem_ipsum' => 123, 'dolor_sit' => 'ipsum']);

        $this->tester->assertEquals($json, JsonHelper::objectToJson(new class {
            public $LoremIpsum = 123;
            public $DolorSit = 'ipsum';
        }));
    }
}