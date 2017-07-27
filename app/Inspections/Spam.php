<?php

namespace App\Inspections;

class Spam
{
    protected $detectors = [
        InvalidKeywords::class,
        KeyHeldDown::class
        
    ];
    public function detect($text)
    {
        $this->text = $text;

        foreach($this->detectors as $dector) {
            app($dector)->detect($text);
        }
        return false;
    }
}