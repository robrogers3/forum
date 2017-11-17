<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $this->signIn();
        
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscribe');

        $this->assertCount(1, $thread->fresh()->subscriptions);
        
        // $thread->addReply([
        //     'user_id' => auth()->id(),
        //     'body' => 'Lorem somethngi yeag'
        // ]);

        // $this->assertCount(1, auth()->user()->notifications);
               
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->delete($thread->path() . '/subscribe');

        $this->assertCount(0, $thread->subscriptions);

        $this->assertCount(0, auth()->user()->notifications);
    }

    /** @test */
    public function a_thread_cannot_be_subscribed_to_twice()
    {
        $this->signIn();

        $thread = create('App\Thread');
        
        $this->post($thread->path() . '/subscribe');

        $this->assertCount(1, $thread->subscriptions);

        $this->expectException(\Illuminate\Database\QueryException::class);

        $this->post($thread->path() . '/subscribe');

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $this->assertFalse($thread->isSubscribedTo);
        
        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
    
}
