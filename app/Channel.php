<?php

namespace App;

use App\Traits\ModelPath;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use ModelPath;

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        $format = '/threads/%s';

        return sprintf($format, $this->slug);
    }
}
