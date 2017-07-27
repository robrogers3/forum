<?php

namespace App;

use App\Traits\ModelPath;
use App\Notifications\YouWereMentioned;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
class Reply extends Model
{
    use ModelPath;
    use Favoritable;
    use RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['owner','favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    //    protected $touches = ['thread'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            //$reply->thread->increment('replies_count');
            $reply->thread->replies_count = $reply->thread->replies_count + 1;
            $reply->thread->save();
        });

        static::deleted(function ($reply) {
            //$reply->thread->decrement('replies_count');
            $reply->thread->replies_count = $reply->thread->replies_count - 1;
            $reply->thread->save();
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function mentionedUsers()
    {
        preg_match_all('/\@([^\s\.]+)/', $this->body, $matches);

        if ($matches)  return $matches[1];
    }
}
