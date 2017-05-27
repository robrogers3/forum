<?php

namespace App\Traits;

use App\Activity;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }
        
        static::created(function ($event) {
             $event->recordActivity($event);
        });
    }

    protected function recordActivity($event)
    {
            $this->activity()->create([
                'type' => $this->getActivityType($event),
                'user_id' => auth()->id()
                ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type = $this->fred();
    }

    private function fred()
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return 'create_' . $type;

        return $type;
    }
}
