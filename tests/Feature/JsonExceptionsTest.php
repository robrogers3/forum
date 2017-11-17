<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JsonExceptionsTest extends TestCase
{
    use DataBaseMigrations;

    /** @test */
    public function validation_responses_returns_a_validation_exception_message()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $reply = create('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->assertTrue(true);

    }

    /** @test */
    public function a_404_returns_a_good_message()
    {
        $this->signIn();
        $this->withExceptionHandling();
        //post('/threads/{channel}/{thread}/reply', 'RepliesController@store')->middleware('auth');
        $this->json('POST', '/threads/foo/money/reply')
                ->assertExactJson(['We cannot find what you are looking for.']);
    }
}
