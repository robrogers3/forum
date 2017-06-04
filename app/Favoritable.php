<?php
namespace App;

use Log;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            Log::log('info', 'should delet');
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if ($this->favorites()->where($attributes)->exists()) {
            $this->favorites()->where($attributes)->get()->each->delete();
        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
