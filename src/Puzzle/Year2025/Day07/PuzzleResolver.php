<?php

namespace App\Puzzle\Year2025\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private const CELL_START = 'S';
    private const CELL_EMPTY = '.';
    private const CELL_SPLITTER = '^';

    protected static int|string $testPart1Expected = 21;
    protected static int|string $testPart2Expected = 40;

    protected static int|string $part1Expected = 1507;
    protected static int|string $part2Expected = 1537373473728;

    public function part1(): int
    {
        $grid = array_map(static fn (string $line): array => str_split($line), $this->getInput()->getArrayData());
        [$y, $x] = $this->findStart($grid);

        return $this->countSplits($grid, $y, $x);
    }

    public function part2(): int
    {
        $grid = array_map(static fn (string $line): array => str_split($line), $this->getInput()->getArrayData());
        [$y, $x] = $this->findStart($grid);

        return $this->countPaths($grid, $y, $x);
    }

    private function findStart(array $grid): array
    {
        foreach ($grid as $y => $row) {
            foreach ($row as $x => $ch) {
                if (self::CELL_START === $ch) {
                    return [$y, $x];
                }
            }
        }
        throw new \RuntimeException('Start cell not found');
    }

    private function countSplits(array $grid, int $y, int $x): int
    {
        $splitCount = 0;
        $height = count($grid);
        $positions = [[$y, $x]];

        while (!empty($positions)) {
            $next = [];
            $placed = [];

            foreach ($positions as [$y, $x]) {
                $ny = $y + 1;

                if ($ny >= $height || !isset($grid[$ny][$x])) {
                    continue;
                }

                $below = $grid[$ny][$x];

                if (self::CELL_EMPTY === $below) {
                    $key = $ny.':'.$x;
                    if (!isset($placed[$key])) {
                        $next[] = [$ny, $x];
                        $placed[$key] = true;
                    }
                    continue;
                }

                if (self::CELL_SPLITTER === $below) {
                    $branches = [];
                    if (isset($grid[$ny][$x + 1]) && self::CELL_EMPTY === $grid[$ny][$x + 1]) {
                        $branches[] = [$ny, $x + 1];
                    }
                    if (isset($grid[$ny][$x - 1]) && self::CELL_EMPTY === $grid[$ny][$x - 1]) {
                        $branches[] = [$ny, $x - 1];
                    }

                    if (count($branches) >= 2) {
                        ++$splitCount;
                    }

                    foreach ($branches as [$by, $bx]) {
                        $key = $by.':'.$bx;
                        if (!isset($placed[$key])) {
                            $next[] = [$by, $bx];
                            $placed[$key] = true;
                        }
                    }
                }
            }

            // no more positions to check
            if ($next === $positions || empty($next)) {
                return $splitCount;
            }

            $positions = $next;
        }

        return $splitCount;
    }

    private function countPaths(array $grid, int $y, int $x, array &$memo = []): int
    {
        $key = $y.':'.$x;
        if (isset($memo[$key])) {
            return (int) $memo[$key];
        }

        // reach bottom
        if (!isset($grid[$y + 1][$x])) {
            return $memo[$key] = 1;
        }

        $below = $grid[$y + 1][$x];

        // move downward
        if (self::CELL_EMPTY === $below) {
            return $memo[$key] = $this->countPaths($grid, $y + 1, $x, $memo);
        }

        // splitter: check both sides
        if (self::CELL_SPLITTER === $below) {
            $sum = 0;
            if (isset($grid[$y + 1][$x + 1]) && self::CELL_EMPTY === $grid[$y + 1][$x + 1]) {
                $sum += $this->countPaths($grid, $y + 1, $x + 1, $memo);
            }
            if (isset($grid[$y + 1][$x - 1]) && self::CELL_EMPTY === $grid[$y + 1][$x - 1]) {
                $sum += $this->countPaths($grid, $y + 1, $x - 1, $memo);
            }

            return $memo[$key] = $sum;
        }

        return $memo[$key] = 0;
    }
}
