<?php

namespace App\Utils;

class Dumper
{
    public static function grid($array)
    {
        foreach ($array as $key => $lines) {
            echo implode('', $lines)."\n";
        }
        echo "\n";
    }
}