<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    /** @test*/
    public function testAdd()
    {
        $result = 30+12;
        $this->assertEquals(42, $result);
    }
}
