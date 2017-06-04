<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Activity;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    /**
     *
     * @param User $user
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }

}
