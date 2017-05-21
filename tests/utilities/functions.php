<?php
function create($class, $attributes = [], $times = null)
{
    $things = factory($class, $times)->create($attributes);
    return $things;
}

function make($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}
