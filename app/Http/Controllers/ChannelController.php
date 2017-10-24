<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:channels,name',
        ]);
        
        Channel::create([
            'name' => request('name'),
            'slug' => request('name')
        ]);
        
        return response('Channel created.', 200);
    }
}
