<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Thread;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManageThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $channelName = $thread->channel->name;
        $data = $thread->toArray();
        $data['channel'] = $channelName;
        $response = $this->post('/threads', $data);
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

    public function guests_cannot_see_the_create_threads_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create/')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }
    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }


    /** @test */
    public function authorized_users_can_delete_a_thread()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path());

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_a_thread()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);
    }

    
    /** @test */
    public function a_threads_channel_can_be_updated()
    {
        //$this->withExceptionHandling()->signIn();
        $this->signIn();
        $thread = create('App\Thread');
        $channel = create('App\Channel');
        
        $this->assertEquals($thread->channel->name, Thread::first()->channel->name);

        $data = $thread->toArray();        

        $data['channel'] = $channel->name;

        $this->patch($thread->path, $data)
            ->assertStatus(200);

        $this->assertEquals($channel->name, Thread::first()->channel->name);
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
