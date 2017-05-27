<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();
        \App\Thread::truncate();
        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'create_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
            ]);

        $activity = \App\Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();
        //\App\Thread::truncate();
        $reply = create('App\Reply');

        $this->assertEquals(2, \App\Activity::count());
    }
}
