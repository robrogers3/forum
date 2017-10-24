<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_confirm_their_accunt()
    {
        $user = create('App\User', ['confirmed' => false]);
        
        
        $this->assertFalse($user->confirmed);

        $user->confirm();

        $this->assertEquals(1, $user->fresh()->confirmed);
    }
}
