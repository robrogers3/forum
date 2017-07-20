<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Filters\ThreadFilters;
use App\Traits\RecordsActivity;
use App\Notifications\ThreadWasUpdated;

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
        $reply =  $this->replies()->create($reply);

        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);

        /** @var ThreadSubscription $subscription */
        // foreach($this->subscriptions as $subscription) {
        //     if ($subscription->user_id != $reply->user_id) {
        //         $subscription->user->notify($reply);
        //     }
        // }
        
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
}
