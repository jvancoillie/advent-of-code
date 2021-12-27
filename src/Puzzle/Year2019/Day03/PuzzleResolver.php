<?php

namespace App\Puzzle\Year2019\Day03;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Distance;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2019/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 6;
    protected static int|string $testPart2Expected = 30;

    protected static int|string $part1Expected = 1674;
    protected static int|string $part2Expected = 14012;

    private array $draws;
    private int $part1;
    private int $part2;

    protected function initialize(): void
    {
        $grid = [];

        foreach ($this->getInput()->getArrayData() as $wire => $wireData) {
            $moves = explode(',', $wireData);
            $grid = $this->drawWire($moves, $grid, $wire);
        }

        $distances = [];
        $intersects = [];

        foreach ($this->draws as $wireA => $drawsA) {
            foreach ($this->draws as $wireB => $drawsB) {
                if ($wireA == $wireB) {
                    continue;
                }
                foreach ($drawsA['keys'] as $key => $value) {
                    if (isset($drawsB['keys'][$key])) {
                        $distances[] = $value;
                        $intersects[] = array_search($value, $drawsA['values']) + 1 + array_search($value, $drawsB['values']) + 1;
                    }
                }
            }
        }

        $min = INF;

        foreach ($distances as $distance) {
            $d = Distance::manhattan([0, 0], $distance);
            $min = min($d, $min);
        }

        $this->part1 = $min;
        $this->part2 = min($intersects);
    }

    public function part1(): int
    {
        return $this->part1;
    }

    public function part2(): int
    {
        return $this->part2;
    }

    private function drawWire($moves, $grid, $wire = 0): array
    {
        $x = $y = 0;

        $grid[$y][$x] = 'O';
        foreach ($moves as $move) {
            preg_match('/(?<direction>[RULD])(?<distance>\d+)/', $move, $m);
            $direction = $m['direction'];
            $distance = $m['distance'];
            switch ($direction) {
                case 'D':
                    while ($distance > 0) {
                        --$distance;
                        ++$y;
                        $grid[$y][$x] = '|';
                        $this->draws[$wire]['keys']["$y|$x"] = [$y, $x];
                        $this->draws[$wire]['values'][] = [$y, $x];
                    }
                    break;
                case 'U':
                    while ($distance > 0) {
                        --$distance;
                        --$y;
                        $grid[$y][$x] = '|';
                        $this->draws[$wire]['keys']["$y|$x"] = [$y, $x];
                        $this->draws[$wire]['values'][] = [$y, $x];
                    }
                    break;
                case 'L':
                    while ($distance > 0) {
                        --$distance;
                        --$x;
                        $grid[$y][$x] = '-';
                        $this->draws[$wire]['keys']["$y|$x"] = [$y, $x];
                        $this->draws[$wire]['values'][] = [$y, $x];
                    }
                    break;
                case 'R':
                    while ($distance > 0) {
                        --$distance;
                        ++$x;
                        $grid[$y][$x] = '-';
                        $this->draws[$wire]['keys']["$y|$x"] = [$y, $x];
                        $this->draws[$wire]['values'][] = [$y, $x];
                    }
                    break;
            }
        }

        return $grid;
    }

    public function dump($grid)
    {
        $minY = min(array_keys($grid)) - 2;
        $maxY = max(array_keys($grid)) + 2;
        $minX = min(array_map(fn ($line) => min(array_keys($line)), $grid)) - 2;
        $maxX = max(array_map(fn ($line) => max(array_keys($line)), $grid)) + 2;

//        dump($grid);
//        dump("$minY => $maxY, $minX => $maxX");

        for ($y = $minY; $y < $maxY; ++$y) {
            $line = [];
            for ($x = $minX; $x < $maxX; ++$x) {
                $line[] = $grid[$y][$x] ?? '.';
            }
            dump(join($line));
        }
    }
}
