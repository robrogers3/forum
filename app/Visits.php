<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }
    
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return (int) Redis::get($this->cacheKey()) ?? 0;        
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}