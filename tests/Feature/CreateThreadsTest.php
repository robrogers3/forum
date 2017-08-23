<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());
        $location = $response->headers->get('Location');

        $this->get($location)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function a_guest_cannot_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());
    }

    /** test */
    public function guests_cannot_see_the_create_threads_page()
    {
        $this->withExceptionHandling()->signIn();
        $this->get('/threads/create/')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $r = $this->publishThread(['title' => null]);
    }
    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $r = $this->publishThread(['body' => null]);
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        $channel = factory('App\Channel', 2)->create()->first();
        $this->expectException('Illuminate\Validation\ValidationException');
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');

        $response = $this->publishThread(['channel_id' => $channel->id]);
    }
    //http://g.co/doodle/wwsn9m
    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
