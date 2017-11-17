<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_finds_stuff()
    {
        $string = 'foobar';
        create('App\Thread', [] , 2);

        create('App\Thread', ['body' => "the body is $string"], 2);

        $results = $this->getJson('/threads/search?q=foobar')->json();

        $this->assertCount(2, $results['data']);

    }
}
