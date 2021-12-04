<?php

namespace App\Utils;

class Grid
{
    /**
     * @param int|string $fill
     *
     * @psalm-param 7|50|1000 $x
     * @psalm-param 3|6|1000 $y
     * @psalm-param ' '|'.'|0 $fill
     *
     * @return array[]
     *
     * @psalm-return array<0|positive-int, array<0|positive-int, mixed>>
     */
    public static function create(int $x, int $y, string|int $fill): array
    {
        $grid = [];

        for ($gy = 0; $gy < $y; ++$gy) {
            for ($gx = 0; $gx < $x; ++$gx) {
                $grid[$gy][$gx] = $fill;
            }
        }

        return $grid;
    }

    public static function dump($array): void
    {
        foreach ($array as $lines) {
            echo implode('', $lines)."\n";
        }
        echo "\n";
    }

    /**
     * @psalm-param '#' $needle
     *
     * @psalm-return 0|positive-int
     */
    public static function count($grid, string $needle): int
    {
        $count = 0;
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if ($grid[$y][$x] === $needle) {
                    ++$count;
                }
            }
        }

        return $count;
    }
}
