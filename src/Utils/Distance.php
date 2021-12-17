<?php

namespace App\Utils;

class Distance
{
    public static function euclidean(array $a, array $b): float|int
    {
        return array_sum(array_map(function ($x, $y) {return abs($x - $y) ** 2; }, $a, $b)) ** (1 / 2);
    }

    public static function manhattan(array $a, array $b): float|int
    {
        return array_sum(array_map(function ($x, $y) {return abs($x - $y); }, $a, $b));
    }
}
