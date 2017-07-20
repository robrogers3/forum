<?php
namespace Tests\Unit;

use App\RomanNumeralsConverter;
use Tests\TestCase;

class RomanNumeralsConverterTest extends TestCase
{
    protected $rc;
    
    public function setUp()
    {
        $this->rc = new RomanNumeralsConverter;
        parent::setUp();
    }

    /** @test */
    public function it_tests_its_a_calculator()
    {
        $this->assertInstanceOf('\App\RomanNumeralsConverter', $this->rc);
    }

    function test1()
    {
        $this->assertEquals('I', $this->rc->c(1));
    }

    function test2()
    {
        $this->assertEquals('II', $this->rc->c(2));
    }

    function test4()
    {
        $this->assertEquals('IV', $this->rc->c(4));
    }
    
    function test5()
    {
        $this->assertEquals('V', $this->rc->c(5));
    }

    function test6()
    {
        $this->assertEquals('VI', $this->rc->c(6));
    }

    function test9()
    {
        $this->assertEquals('IX', $this->rc->c(9));
    }
    
    function test10()
    {
        $this->assertEquals('X', $this->rc->c(10));
    }

    function test13()
    {
        $this->assertEquals('XIII', $this->rc->c(13));
    }

    function test15()
    {
        $this->assertEquals('XV', $this->rc->c(15));
    }

    function test18()
    {
        $this->assertEquals('XVIII', $this->rc->c(18));
    }

    function test20()
    {
        $this->assertEquals('XX', $this->rc->c(20));
    }

    function test23()
    {
        $this->assertEquals('XXIII', $this->rc->c(23));
    }

    function test50()
    {
        $this->assertEquals('L', $this->rc->c(50));
    }

    function test54()
    {
        $this->assertEquals('LIV', $this->rc->c(54));
    }

    function test59()
    {
        $this->assertEquals('LIX', $this->rc->c(59));
    }
    function test100()
    {
        $this->assertEquals('C', $this->rc->c(100));
    }

    function test151()
    {
        $this->assertEquals('CLI', $this->rc->c(151));
    }

    function test210()
    {
        $this->assertEquals('CCX', $this->rc->c(210));
    }

    function test580()
    {
        $this->assertEquals('DLXXX', $this->rc->c(580));
    }
    function test900()
    {
        $this->assertEquals('CM', $this->rc->c(900));
    }

    //MCMXCIX
    function test1999()
    {
        $this->assertEquals('MCMXCIX', $this->rc->c(1999));
    }

    function test2000()
    {
        $this->assertEquals('MM', $this->rc->c(2000));
    }

    function test2017()
    {
        $this->assertEquals('MMXVII', $this->rc->c(2017));
    }

    function test3994()
    {
        $this->assertEquals('MMMCMXCIV', $this->rc->c(3994));
    }
}