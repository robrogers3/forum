<?php
namespace App;

class RomanNumeralsConverter
{
    public function c($n)
    {
        return $this->convert($n);
    }
    public function convert($number)
    {
        $map = [
            1000 => 'M',
            900 => 'CM',
            500 => 'D',
            400 => 'CD',
            100 => 'C',
            90 => 'XC',
            50 => 'L',
            40 => 'XL',
            10 => 'X',
            9 => 'IX',
            5 => 'V',
            4 => 'IV',
            1 => 'I',
        ];

        $conversion = '';
        foreach($map as $l => $s) {
            while ($number >= $l) {
                $conversion .= $s;
                $number -= $l;
            }
        }

        return $conversion;
    }
}
