<?php

namespace App;

use App\Traits\ModelPath;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use ModelPath;
    use Favoritable;
    use RecordsActivity;

    protected $guarded = ['id'];

    protected $with = ['owner','favorites'];


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
