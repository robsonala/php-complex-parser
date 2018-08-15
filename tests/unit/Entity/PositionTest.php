<?php
namespace Entity;

use PHPComplexParser\Entity\BaseEntity;
use PHPComplexParser\Entity\Position;

class PositionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Position
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Position();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Position);
    }

    public function testLine()
    {
        $num = rand(100,999);

        $this->objEntity->setLine($num);

        $this->tester->assertEquals($num, $this->objEntity->getLine());
    }
}