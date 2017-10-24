<?php

namespace App\Http\Controllers;


use App\Thread;
use App\Trending;
use App\Channel;
use App\Filters\ThreadFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use RobRogers3\LaravelExceptionHandler\Exceptions\MessagingException;
class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);
	
        if (request()->wantsJson()) {
            return $threads->get();
        }
        //$threads = $threads->get();
        $threads = $threads->paginate(5);
        return view('threads.index',
                    ['threads' => $threads, 'trending' => $trending->get() ,'channel' => $channel->exists ? $channel : null]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'channel' => 'required_without:channel_id',
            'channel_id' => 'required_without:channel|exists:channels,id',
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
        ]);

        
        $channel = $this->fetchChannel();

        if (!$channel) {
            $channel = Channel::create([
                'name' => request('channel'),
                'slug' => request('channel')
            ]);
        }

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $channel->id,
            'title' => request('title'),
            'body'  => request('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->visits()->record();
        
        return view('threads.show', [
            'thread' => $thread,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Thread              $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required',
            'channel' => 'required'
        ]);

        $channel = Channel::whereName(request('channel'))->firstOrFail();

        $data = request()->only('title', 'body');

        $data['channel_id'] = $channel->id;

        $thread->update($data);

        return response($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return response(['path'=>'threads'], 200);
    }

    protected function getThreads(Channel $channel, $filters)
    {
        $threads = Thread::latest()->with('channel')->filter($filters);

        if ($channel->exists) {
            $threads = $threads->where('channel_id', $channel->id);
        }

        return $threads;
    }

    protected function fetchChannel()
    {
        if (request('channel_id')) {
            return Channel::findOrFail(request('channel_id'));
        }
        
        return Channel::whereName(request('channel'))->first();
    }
}
