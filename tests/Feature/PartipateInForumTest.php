<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DataBaseMigrations;

    /** @test */
    public function an_authenticated_user_can_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = factory('App\Thread')->create();

        $reply = make('App\Reply');//->make();

        $this->post($thread->path() .  '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_participate_in_forum_threads()
    {

        //$user = factory('App\User')->create();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->create();

        $this->post('/threads/some-channel/ '. $thread->id . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_a_reply()
    {
        $this->withExceptionHandling();
        
        $reply = create('App\Reply');

        $this->delete("/reply/{$reply->id}")->assertRedirect('/login');

        $reply = create('App\Reply');
        
        $this->signIn();
        
        $this->delete("/reply/{$reply->id}")
            ->assertStatus(403);

    }

    /** @test */
    public function authorized_users_can_delete_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/reply/{$reply->id}")->assertStatus(302);
        
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

        /** @test */
    public function authorized_users_can_update_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $body = "you have been changed";

        $this->patch("/reply/{$reply->id}", ['body' => $body]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $body]);
    }
}
