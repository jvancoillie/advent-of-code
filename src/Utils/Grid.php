<?php

namespace App\Utils;

class Grid
{
    public static function create($x, $y, $fill)
    {
        $grid = [];

        for ($gy = 0; $gy < $y; $gy++) {
            for ($gx = 0; $gx < $x; $gx++) {
                $grid[$gy][$gx] = $fill;
            }
        }

        return $grid;
    }

    public static function dump($array)
    {
        foreach ($array as $key => $lines) {
            echo implode('', $lines)."\n";
        }
        echo "\n";
    }

    public static function count($grid, $needle)
    {
        $count = 0;
        for ($y = 0; $y < count($grid); $y++) {
            for ($x = 0; $x < count($grid[$y]); $x++) {
                if ($grid[$y][$x] === $needle) {
                    $count++;
                }
            }
        }

        return $count;
    }
}
