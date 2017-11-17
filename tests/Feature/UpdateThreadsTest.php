<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling()->signIn();
    }

    /** @test */
    public function a_thread_can_be_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertEquals($thread->title, Thread::first()->title);
        
        $thread->title = 'Updated via patch';

        $data = $thread->toArray();

        $this->patchJson($thread->path, $data)
            ->assertStatus(200)->assertSee($thread->title);

        tap($thread->fresh(), function($thread) {
            $this->assertEquals('Updated via patch', $thread->title);  
        });
    }

    /** @test */
    public function unauthorized_users_cannot_update_a_thread()
    {
        $thread = create('App\Thread', ['body' => '', 'user_id' => create('App\User')->id]);

        $data = $thread->toArray();

        $this->patchJson($thread->path, $data)
            ->assertStatus(403)->assertSee('Sorry you are not authorized for this request.');

    }
    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        
        $thread = create('App\Thread', ['body' => '', 'user_id' => auth()->id()]);

        $data = $thread->toArray();

        $this->patchJson($thread->path, $data)
            ->assertStatus(422)->assertSee('The body field is required.');

    }
}
