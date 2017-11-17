<?php

namespace App\Http\Controllers;

use App\Reply;
use App\User;
use App\Thread;
use App\Http\Forms\CreatePostForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RobRogers3\LaravelExceptionHandler\Exceptions\MessagingException;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(4);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Spam $spam)
    {
        $this->spam = $spam;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        if ($thread->locked) {
            return response('This thread has been locked down.', 418);
        }
        return $thread->addReply(
            [
                'body' => request('body'),
                'user_id' => auth()->id()
            ])->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reply               $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => 'required|spamfree'
        ]);
        
        $reply->update(['body' => request('body')]);

        return response('update worked');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return response('Reply Deleted', 200);
    }
}
