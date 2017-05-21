<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular'];

    public function by($username)
    {
        $user = \App\User::where('name', $this->request->by)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders  = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }
}
