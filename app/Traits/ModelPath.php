<?php

namespace App\Traits;

trait ModelPath
{
    public function path()
    {
        $identifier = $this->getRouteKeyName();

        $formant = '/%s/%d';
        if (!is_numeric($identifier)) {
            $format = '/%s/%s';
            $identifier = $this->{$identifier};
        }

        return sprintf($format, str_plural(strtolower(class_basename($this))), $identifier);
    }
}