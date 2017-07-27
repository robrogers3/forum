<?php

namespace App;

use Carbon\Carbon;
use App\Filters\ThreadFilters;
use App\Traits\RecordsActivity;
use App\Notifications\YouWereMentioned;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //use ModelPath;
    use RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['creator', 'channel'];

    protected $appends =  ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($thread) {
            $thread->replies->each->delete();
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
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));
        
        return $reply;
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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
    
    public function subscribe($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }

        $this->subscriptions()->create([
            'user_id' => $userId
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }

        $this->subscriptions()->delete([
            'user_id' => $userId
        ]);
    }

    /**
     * Determine if the current user is subscribed to the thread.
     *
     * @return boolean
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
    public function hasUpdatesFor($user = null)
    {
        $user = $user ?: auth()->user();

        if (!$user) return false;
        
        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }
}
