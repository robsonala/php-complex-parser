<?php
namespace Component;

use PHPComplexParser\Component\ArrayHelper;

class ArrayHelperTest extends \Codeception\Test\Unit
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

    public function testTranspose()
    {
        $original = [
            [1,2,3],
            [4,5,6],
            [7,8,9]
        ];

        $transposed = [
            [1,4,7],
            [2,5,8],
            [3,6,9]
        ];

        $this->tester->assertEquals($transposed, ArrayHelper::transpose($original));
    }
}