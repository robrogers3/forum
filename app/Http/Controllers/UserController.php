<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $search = request('name');
        return User::where('name', 'LIKE', "$search%")->pluck('name');
    }

    public function confirmAccount()
    {
        if (!request('user_id')) {
            return redirect('/threads')->with('flash', 'Humm, we could not look you up.');
        }
        try {
            User::firstOrFail('user_id', request('user_id'))->confirm();
            
            return redirect('/threads')->with('flash', 'Your account has been confirmed');
        } catch (\Exception $e) {
            return redirect('/threads')->with('flash', $e->getMessage());
        }
    }
}
