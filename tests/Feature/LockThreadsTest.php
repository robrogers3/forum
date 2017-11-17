<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function once_locked_a_thread_maynot_receive_any_replies()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $thread->lock();
        
        $response = $this->post($thread->path() . '/reply', [
            'body' => 'foobar',
            'user_id' => auth()->id()
        ])->assertStatus(418);

        $this->assertEquals('This thread has been locked down.', $response->content());
    }

    /** @test */
    public function a_non_administrator_cannot_lock_a_thread()
    {
        $this->signIn()->withExceptionHandling();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked.threads.store', $thread), [])
            ->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_lock_a_thread()
    {
        $this->withExceptionHandling();
        
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked.threads.store', $thread), [])
            ->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }

    /** @test */
    public function an_administrator_can_unlock_a_thread()
    {
        $this->withExceptionHandling();
        
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked.threads.destroy', $thread))
            ->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked);
    }
}
