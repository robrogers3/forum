<?php

namespace App\Inspections;

class KeyHeldDown
{
    
    public function detect($text)
    {
        if (preg_match('/(.)\\1{4,}/', $text)) {
            throw new \Exception('spam detected'); 
        }
    }
}