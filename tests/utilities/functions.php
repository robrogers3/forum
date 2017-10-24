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

function ddd($arg)
{
    if (is_array($arg) || is_object($arg)) {
        print_r($arg);
    } else {
        echo $arg . PHP_EOL;
    }
    exit;
}

function m($obj) {
    return get_class_methods(get_class($obj));
}