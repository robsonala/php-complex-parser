<?php
namespace Entity;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\General;

class GeneralTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var General
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new General();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new General);
    }

    public function testIgnoreLinesBegin()
    {
        $num = rand(100,999);
        $this->objEntity->setIgnoreLinesBegin($num);

        $this->tester->assertEquals($num, $this->objEntity->getIgnoreLinesBegin());
    }
}