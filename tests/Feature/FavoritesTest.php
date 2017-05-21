<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_unauthenticed_user_cannot_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('/reply/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('/reply/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

        /** @test */
    public function an_authenticated_user_can_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
        $this->post('/reply/' . $reply->id . '/favorites');

        $this->post('/reply/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Dont insert same record twice.');
        }

        $this->assertCount(1, $reply->favorites);
    }
}
