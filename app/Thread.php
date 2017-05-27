<?php

namespace App;

//use App\Traits\ModelPath;
use Illuminate\Database\Eloquent\Model;
use App\Filters\ThreadFilters;
use App\Traits\RecordsActivity;

class Thread extends Model
{
    //use ModelPath;
    use RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

    }

    public function delete()
    {
        $this->replies()->each(function ($reply) {
            $reply->delete();

        });
        
        parent::delete();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
        ->withCount('favorites')
        ->with('owner');
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
