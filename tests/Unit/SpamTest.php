<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Inspections\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    /** @test */
    public function it_detects_invalid_keywonds()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('innocent reply'));

        $this->expectException(\Exception::class);

        $spam->detect('yahoo customer support');
    }

    /** @test */
    public function it_detects_when_a_key_is_held_down()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('hello'));

        $this->expectException(\Exception::class);

        $spam->detect('hellow you zzzzzz');
    }
}