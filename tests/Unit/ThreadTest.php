<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
        
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->id, $thread->path());
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $this->thread->replies
        );
    }
    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }
    /** @test */
    public function a_thread_can_add_a_reply()
    {
        \App\Reply::truncate();
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        \App\Thread::truncate();
        
        $thread = make('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe(1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', 1)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
 
        $thread->unsubscribe(1);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', 1)->count());
    }
}
