<?php
namespace Component;

use PHPComplexParser\Component\JsonHelper;

class JsonHelperTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \GenericClassMyTest
     */
    protected $objGeneric;
    
    protected function _before()
    {
        $this->objGeneric = new \GenericClassMyTest();
    }

    protected function _after()
    {
    }

    public function testObjectToJsonGeneric()
    {   
        $json = json_encode((object)['LoremIpsum' => $this->objGeneric->LoremIpsum, 'DolorSit' => $this->objGeneric->DolorSit]);

        $this->tester->assertEquals($json, JsonHelper::objectToJson($this->objGeneric));
    }
    public function testJsonToObjectGeneric()
    {
        $json = json_encode((object)['LoremIpsum' => $this->objGeneric->LoremIpsum, 'DolorSit' => $this->objGeneric->DolorSit]);

        $this->tester->assertEquals($this->objGeneric, JsonHelper::jsonToObject($json, \GenericClassMyTest::class));
    }
}