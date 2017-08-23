<?php
namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

        return response('', 200);
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        if (request()->wantsJson()) {
            return response('', 200);
        }
    }
}
