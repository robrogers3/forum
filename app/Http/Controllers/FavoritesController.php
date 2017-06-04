<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     * @param Reply $reply
     */

    public function store(Reply $reply)
    {
        $reply->favorite();

        if (request()->wantsJson()) {
            return response('', 200);
        }
        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        if (request()->wantsJson()) {
            return response('', 200);
        }
    }
}
