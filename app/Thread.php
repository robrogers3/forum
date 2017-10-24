<?php

namespace App;

use Carbon\Carbon;
use App\Filters\ThreadFilters;
use App\Traits\RecordsActivity;
use App\Notifications\YouWereMentioned;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    //use ModelPath;
    use RecordsActivity;

    protected $fillable = [
        'user_id',
        'channel_id',
        'title',
        'body',
        'slug',
        'best_reply_id',
        'locked'
    ];

    protected $with = ['creator', 'channel'];

    protected $appends =  ['isSubscribedTo', 'path'];

    protected $casts  = ['locked' => 'boolean'];
    
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });

        static::created(function ($thread) {
            $thread->update(['slug' => str_slug($thread->title)]);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
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

    
    public function getPathAttribute()
    {
        if (!$this->channel) {
            return '';
        }
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }
    
    public function path()
    {
        return  "/threads/{$this->channel->slug}/{$this->slug}";

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

    public function visits()
    {
        return new Visits($this);
    }

    public function setSlugAttribute($value)
    {

        $original = $slug = str_slug($value);
        $count = 2;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        $this->attributes['slug'] = $slug;

    }

    public function incrementSlug($slug, $count = 2)
    {
        $template = static::whereTitle($this->title)->latest('id')->value('slug');

        preg_match('/-(\d+)$/', $template, $matches);
        
        if (!$matches) {
            return $template . '-2';
        }

        $suffix = sprintf('-%d', (int) $matches[1] + 1);

        $slug = str_replace($matches[0], $suffix, $template);

        return $slug;
        
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function unMarkBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => null]);
    }

    public function lock()
    {
        $this->update(['locked' => true]);
    }
}
