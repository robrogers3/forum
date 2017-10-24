<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Mail\ConfirmEmailAddress;
use Swift_Events_EventListener;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfirmEmailTest extends TestCase
{

    public $message;

    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();

        Mail::getSwiftMailer()
            ->registerPlugin(new TestingConfirmationEmailEventListener($this));
    }
    
    /** test */
    public function a_confirm_email_can_be_sent()
    {
        $this->signIn();

        Mail::to('robrogers@me.com')->send(new ConfirmEmailAddress(auth()->user()));

        $this->assertEquals('Confirm Email Address', $this->message->getSubject());
    }

    public function addEmail($message)
    {
        $this->message = $message;
    }
}

class TestingConfirmationEmailEventListener implements Swift_Events_EventListener
{
    public $test;
    
    public function __construct($test)
    {
        $this->test = $test;
    }
    
    public function beforeSendPerformed($event)
    {
        $message = $event->getMessage();

        $this->test->addEmail($message);
    }
}
