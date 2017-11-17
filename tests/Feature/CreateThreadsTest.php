<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Thread;
use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            $m = \Mockery::mock(Recaptcha::class);

            $m->shouldReceive('passes')->andReturn(true);

            return $m;
        });
    }
    
    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $this->signIn()->withExceptionHandling();

        $thread = make('App\Thread');
        
        $response = $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
        
        $location = $response->headers->get('Location');

        $this->get($location)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function new_users_must_confirm_their_email_address_before_creating_threads()
    {
        Mail::fake();
        
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->withExceptionHandling()->signIn($user);
        $thread = make('App\Thread');

        
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash', 'You must confirm your email address. Please check your email');
    }
        
    
    /** @test */
    public function a_guest_cannot_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());
    }

    /** test */
    public function guests_cannot_see_the_create_threads_page()
    {
        $this->withExceptionHandling()->signIn();
        $this->get('/threads/create/')
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_requires_recaptcha_validation()
    {
        unset(app()[Recaptcha::class]);
        $this->withExceptionHandling();        
        $this->publishThread(['g-recaptcha-response' => 'test'])
                                                     ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->expectException('Illuminate\Validation\ValidationException');
        $r = $this->publishThread(['title' => null]);
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->withExceptionHandling();
        $this->publishThread(['body' => null])
                                     ->assertSessionHasErrors('body')
                                     ->assertStatus(302);
    }

    /** @test */
    public function a_thread_requires_an_unique_slug()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['title' => 'foo-title']);

        $this->assertEquals('foo-title', $thread->fresh()->slug, 'foo-ttile should be slug');

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

    /** @test */
    public function a_thread_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $this->assertEquals('some-title-24', $thread->fresh()->slug);

        $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->assertTrue(Thread::whereSlug('some-title-24-2')->exists());

    }
    
    /** @test */
    public function a_thread_requires_a_valid_channel()
    {

        $this->withExceptionHandling();
        $channel = factory('App\Channel', 2)->create()->first();

        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
        
        //$this->expectException('Illuminate\Validation\ValidationException');
         $this->publishThread(['channel_id' => null])
                                            ->assertSessionHasErrors('channel_id');

    }

    /** @test */
    public function a_thread_can_be_posted_with_a_valid_channel()
    {
        $this->withExceptionHandling();

        $this->publishThread()->assertStatus(302);

        $thread = make('App\Thread');
        
        $this->post(route('threads'), $this->channelizeThread($thread))->assertStatus(302);
    }
    //http://g.co/doodle/wwsn9m
    public function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

    protected function channelizeThread($thread)
    {
            $data = $thread->toArray();
            $data['channel'] = $thread->channel->name;
            return $data;
    }
}
