<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create('App\User', ['name' => 'JaneDoe']);

        $thread = create('App\Thread');

        $reply = create('App\Reply', ['body' => 'hey @JaneDoe @JohnDoe said hi']);

        $this->json('post', $thread->path() . '/reply', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function mentioned_users_in_a_reply_are_notified_unless_they_mentioned_themselves()
    {
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create('App\User', ['name' => 'JaneDoe']);

        $thread = create('App\Thread');

        $reply = create('App\Reply', ['body' => 'hey @JaneDoe @JohnDoe said hi']);

        $this->json('post', $thread->path() . '/reply', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}
