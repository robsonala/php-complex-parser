<?php
namespace Entity;

use PHPComplexParser\Entity\{BaseEntity, Column};

class ColumnTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Column
     */
    protected $objEntity;
    
    protected function _before()
    {
        $this->objEntity = new Column();
    }

    protected function _after()
    {
    }

    public function testClassExtension()
    {
        $this->tester->assertInstanceOf(BaseEntity::class, new Column);
    }
}