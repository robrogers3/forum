<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
        
        return response('', 204);
    }

    public function unmark(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->unMarkBestReply($reply);
        
        return response('', 204);
    }
}
