<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_notificiation_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();
        
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Lorem somethngi yeag'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
    
    /** @test */
    public function a_notificiation_is_not_prepared_when_a_subscribed_thread_receives_a_new_repy_from_the_reply_user()
    {
        $this->signIn();
        
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Lorem somethngi yeag'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();
        
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Lorem somethngi yeag'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

        $user = auth()->user();

        $notification = auth()->user()->unreadNotifications->first()->id;

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);
    }
    
    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signIn();
        
        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Lorem somethngi yeag'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);

        $user = auth()->user();

        $notification = auth()->user()->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notification}");

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
