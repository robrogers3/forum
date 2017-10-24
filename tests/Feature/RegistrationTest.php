<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function users_can_fully_confirm_their_email_addresses()
    {
        Mail::fake();
        
        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        $user = \App\User::whereName('John')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        //confirm account
        $response = $this->get(route('register.confirm', ['token' =>  $user->confirmation_token]));

        $this->assertTrue($user->fresh()->confirmed);

        $this->assertNull($user->fresh()->confirmation_token);

        $response->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    }

    /** @test */
    public function confirming_an_invalid_token_yields_an_error_message()
    {
        $this->get(route('register.confirm', ['token' =>  'invalid']))
                                                      ->assertRedirect(route('threads'))
                                                      ->assertSessionHas('flash', 'We cannot confirm your account. Something went awry.');
    }
}
