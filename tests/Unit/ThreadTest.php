<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Thread;
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
    function a_thread_has_a_path()
    {
        $thread = create('App\Thread');
        
        $this->assertEquals('/threads/' . $thread->channel->slug . '/' . $thread->slug, $thread->path());
    }

    /** @test */
    public function a_thread_has_a_unique_slug()
    {

        $this->assertNotNull($this->thread->slug);
        Thread::truncate();
        $thread2 = Thread::create($this->thread->toArray());

        $this->assertEquals($this->thread->slug . '-2', $thread2->slug);

        $thread3 = Thread::create($this->thread->toArray());

        $this->assertEquals($this->thread->slug . '-3', $thread3->slug);


        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());
        $thread4 = Thread::create($this->thread->toArray());        
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

    /** @test */
    public function a_thread_can_order_its_replies_by_the_best_one()
    {
        $thread = create('App\Thread');

        $replies = create('App\Reply', ['thread_id' => $thread->id], 20);

        $replies[1]->thread->update(['best_reply_id' => $replies[19]->id]);

        $this->assertTrue($replies[19]->fresh()->isBest());

        $sorted = $thread->replies()->paginate()->sortByDesc(function($reply)  {
            return $reply->isBest();
        })->first();

        $this->assertEquals($replies[19]->id, $sorted->id);
        //ddd($sorted);
    }

        /** @test */
    public function a_thread_can_be_locked()
    {
        $thread = create('App\Thread');

        $this->assertFalse($thread->locked);

        $thread->lock();
        
        $this->assertTrue($thread->locked);
    }
}
