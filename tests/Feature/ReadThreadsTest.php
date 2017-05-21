<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_reader_can_view_all_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_see_replies_associated_with_a_thread()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        $this->get($this->thread->path())->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel', ['name' => 'money']);

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id, 'title' => 'money']);
        $threadNotInChannel = create('App\Thread', ['title' => 'boingo']);

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title, 'I should see money')
            ->assertDontSee($threadNotInChannel->title, 'I should not se boinge');
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $johnsThread = create('App\Thread', ['user_id' => auth()->id(), 'title' => 'MONEY MONEY']);
        $otherThread = create('App\Thread');
        $this->get('/threads?by=JohnDoe')
            ->assertSee($johnsThread->title)
            ->assertDontSee($otherThread->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWith3 = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWith3->id], 3);
        $threadWith2 = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWith2->id], 2);
        $threadOne = $this->thread;

        $response = $this->getJson('/threads?popular=JonDoe')->json();

        $order = array_column($response, 'replies_count');

        $this->assertEquals($order, array(3,2,0));
    }
}
