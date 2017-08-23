<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function unread($thread)
    {
        cache()->forget(
            $this->visitedThreadCacheKey($thread)
        );
    }
    
    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }
    
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ?: '/avatars/avatar.png');
    }

    public function avatar()
    {
        return $this->getAvatarPathAttribute($this->avatar_path);
    }
}
