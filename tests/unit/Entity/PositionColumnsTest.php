<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity,Position,PositionColumns};

class PositionColumnsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var PositionColumns
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new PositionColumns();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new PositionColumns);
    }

    public function testColumnValid()
    {
        $num = rand(100,999);
        $this->objEntity->setColumn($num);

        $this->tester->assertEquals($num, $this->objEntity->getColumn());
    }

    /**
     * @expectedException \Exception
     */
    public function testColumnInvalid()
    {
        $this->objEntity->setColumn(-1);
    }

    public function testSearch()
    {
        $str = uniqid();
        $this->objEntity->setSearch($str);

        $this->tester->assertEquals($str, $this->objEntity->getSearch());
    }

    public function testSearchColumnValid()
    {
        $num = rand(100,999);
        $this->objEntity->setSearchColumn($num);

        $this->tester->assertEquals($num, $this->objEntity->getSearchColumn());
    }

    /**
     * @expectedException \Exception
     */
    public function testSearchColumnInvalid()
    {
        $this->objEntity->setSearchColumn(-1);
    }

    public function testHeaderMatch()
    {
        $str = uniqid();
        $this->objEntity->setHeaderMatch($str);

        $this->tester->assertEquals($str, $this->objEntity->getHeaderMatch());
    }

    public function testRangeValid()
    {
        $num = rand(100,499);
        $num2 = rand(500,999);
        $this->objEntity->setRange($num, $num2);

        $this->tester->assertEquals([$num, $num2], $this->objEntity->getRange());

        $this->objEntity->setRange($num, null);

        $this->tester->assertEquals([$num, null], $this->objEntity->getRange());
    }
    
    /**
     * @expectedException \Exception
     */
    public function testRangeInvalid()
    {
        try {
            $this->objEntity->setRange(null, null);
            $this->tester->fail('Expected TypeError');
        } catch (\Exception $e) {
            $this->tester->fail('Expected TypeError');
        } catch (\Error $e) {
            throw new \Exception();
        }
    }

}