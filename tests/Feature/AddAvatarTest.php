<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function only_members_can_add_avatars()
    {
        $this->withExceptionHandling();
        
        $this->json('POST', "/api/users/1/avatar")->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->withExceptionHandling()->signIn();

        $id = auth()->id();
        $name = auth()->user()->name;
        $this->json('POST', "/api/users/{$name}/avatar", ['avatar' => 'not-an-image'])->assertStatus(422);
    }

    /** @test */
    public function a_user_may_add_an_avatar_to_their_profile()
    {
        //        $this->withExceptionHandling()->signIn();
        $this->signIn();
        Storage::fake('public');
        $id = auth()->id();
        $name = auth()->user()->name;

        $this->json('POST', "/api/users/{$name}/avatar", [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ])->assertStatus(200);

        Storage::disk('public')->assertExists('avatars/'. $file->hashName());

        $this->assertEquals(auth()->user()->avatar_path, url('avatars/' . $file->hashName()));
    }
}
