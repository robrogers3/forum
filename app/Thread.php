<?php

namespace App;

//use App\Traits\ModelPath;
use Illuminate\Database\Eloquent\Model;
use App\Filters\ThreadFilters;

class Thread extends Model
{
    //use ModelPath;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function path()
    {
        $path = "/threads/{$this->channel->slug}/{$this->id}";
        return $path;
    }

    public function scopeFilter($builder, ThreadFilters $filter)
    {
        return $filter->apply($builder);
    }
}
