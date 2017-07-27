<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user)
    {
        $unread = auth()->user()->unreadNotifications;

        return auth()->user()->unreadNotifications;
    }
    
    public function destroy(User $user, $notificationId)
    {
        $user = auth()->user();

        $not = auth()->user()->notifications()->findOrFail($notificationId);

        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();

        return response("marked");
    }
}
