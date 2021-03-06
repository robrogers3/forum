<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */

    public function a_thread_creator_can_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-reply.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function the_thread_creator_can_unmark_thread_as_best()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $this->postJson(route('best-reply.store', [$reply->id]));

        $this->assertTrue($reply->fresh()->isBest());

        $this->postJson(route('best-reply.unmark', [$reply->id]))->assertStatus(204);

        $this->assertFalse($reply->fresh()->isBest());
        
    }
}
