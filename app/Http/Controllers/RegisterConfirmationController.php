<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            $this->validate(request(), [
                'token' => 'required'
            ]);

            User::whereConfirmationToken(request('token'))->firstOrFail()->confirm();
            
            return redirect(route('threads'))->with('flash', 'Your account is now confirmed.');
        } catch (\Exception $e) {
            return redirect(route('threads'))->with('flash', 'We cannot confirm your account. Something went awry.');
        }
    }
}
