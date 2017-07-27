<?php

namespace App\Inspections;

class InvalidKeywords
{
    protected  $invalidKeywords = [
            'yahoo customer support',
            'spam'
    ];
    
    public function detect($text)
    {
        foreach ($this->invalidKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                throw new \Exception('spam');
            }
        }    
    }
}