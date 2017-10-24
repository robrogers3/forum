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
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling();
        //$this->expectException('Illuminate\Auth\AuthenticationException');
        
        $this->post('/threads/some-channel/1/reply', [])->assertStatus(302);
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => 'oh yes']);
        $this->post($thread->path() . '/reply', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    
    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn()->withExceptionHandling();
        
        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->json('POST', $thread->path() . '/reply', $reply->toArray())
                                            ->assertStatus(422);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_a_reply()
    {

        //$this->withExceptionHandling();
        $this->signIn();
        
        $reply = create('App\Reply');
        
        $this->expectException('Exception');
        
        $this->delete("/reply/{$reply->id}");
    }

    /** @test */
    public function authorized_users_can_delete_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(200);
        
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

        /** @test */
    public function authorized_users_can_update_a_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $body = "you have been changed";

        $this->patch("/replies/{$reply->id}", ['body' => $body]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $body]);
    }

    /** @test */
    public function replies_that_are_spam_cannot_be_created()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $reply = create('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->json('POST', $thread->path() . '/reply', $reply->toArray())->assertStatus(422);
    }
}
