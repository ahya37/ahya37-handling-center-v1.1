<?php 

namespace App\Helpers;
use App\Helpers\MicroTime;

class StrRandom {

    public static function generate($length=30)
    {
        $microTime = MicroTime::generate(15);
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$microTime.'abcdefghijklmnopqrstuvwxyz';
        $stringLength = strlen($string);
        $stringRandom = '';

        for ($i=0; $i < $length ; $i++) { 
            $stringRandom .= $string[rand(0, $stringLength - 1)];
        }

        return $stringRandom;
    
    }
}