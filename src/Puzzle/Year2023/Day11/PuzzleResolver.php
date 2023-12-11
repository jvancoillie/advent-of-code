<?php

namespace App\Puzzle\Year2023\Day11;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Distance;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 374;
    protected static int|string $testPart2Expected = 82000210;

    protected static int|string $part1Expected = 9693756;
    protected static int|string $part2Expected = 717878258016;

    protected int $part1 = 0;
    protected int $part2 = 0;

    public function initialize(): void
    {
        $universe = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());
        $galaxies = [];
        $empty = ['rows' => [], 'columns' => range(0, count($universe[0]) - 1)];

        foreach ($universe as $x => $row) {
            $unique = array_unique($row);

            if (1 === count($unique) && '.' === $unique[0]) {
                $empty['rows'][] = $x;
            }

            foreach ($row as $y => $cell) {
                if ('.' !== $cell && isset($empty['columns'][$y])) {
                    unset($empty['columns'][$y]);
                }

                if ('#' === $cell) {
                    $galaxies[] = [$x, $y];
                }
            }
        }

        $pairs = [];

        foreach ($galaxies as $i => [$xa, $ya]) {
            foreach ($galaxies as $j => [$xb, $yb]) {
                if ($i === $j) {
                    continue;
                }

                if (isset($pairs["$xa|$ya|$xb|$yb"]) || isset($pairs["$xb|$yb|$xa|$ya"])) {
                    continue;
                }

                $pairs["$xa|$ya|$xb|$yb"] = 1;

                $emptyRows = count(array_intersect($empty['rows'], range(min($xa, $xb), max($xa, $xb))));
                $emptyColumns = count(array_intersect($empty['columns'], range(min($ya, $yb), max($ya, $yb))));

                $distance = Distance::manhattan([$xa, $ya], [$xb, $yb]);

                $this->part1 += $distance + $emptyRows + $emptyColumns;
                $this->part2 += $distance + $emptyRows * 999999 + $emptyColumns * 999999;
            }
        }
    }

    public function part1(): int
    {
        return $this->part1;
    }

    public function part2(): int
    {
        return $this->part2;
    }
}
