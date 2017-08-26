<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\ThreadWasUpdated;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Notification;
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

    /** test */
    public function a_thread_notifies_all_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $id = auth()->id();

        $this->thread
            ->subscribe($id)
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 99999999999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);        
    }

    /** @test */
    public function a_thread_can_check_if_an_user_has_read_all_replies()
    {
        $this->signIn();
        $thread = create('App\Thread');
        
        tap(auth()->user(), function($user) use ($thread) {

            $this->assertTrue($thread->hasUpdatesFor());

            auth()->user()->read($thread);
            
            $this->assertFalse($thread->hasUpdatesFor());

            $thread->addReply([
                'user_id' => 9999,
                'body' => 'something cool'
            ]);

            //$this->assertTrue($thread->hasUpdatesFor());
        });
    }

    /** @test */
    public function a_thread_records_each_visit()
    {
        $thread = make('App\Thread', ['id' => 1]);

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());
        
        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(2, $thread->visits()->count());
    }
}
