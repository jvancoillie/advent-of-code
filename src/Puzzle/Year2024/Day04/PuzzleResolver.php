<?php

namespace App\Puzzle\Year2024\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 18;
    protected static int|string $testPart2Expected = 9;

    protected static int|string $part1Expected = 2562;
    protected static int|string $part2Expected = 1902;

    public function part1(): int
    {
        $xmas = ['X', 'M', 'A', 'S'];

        $r = $this->search($xmas, Grid::$fullDirections);

        return count($r);
    }

    public function part2(): int
    {
        $xmas = ['M', 'A', 'S'];

        $r = $this->search($xmas, Grid::$diagDirections);

        $ans = 0;
        // todo improve search A point with memoize
        foreach ($r as $entry) {
            [$sy, $sx] = $entry[0];
            [$ey, $ex] = $entry[1];

            $a = [[$sy, $ex], [$ey, $sx]];
            $b = [[$ey, $sx], [$sy, $ex]];

            if (in_array($a, $r) || in_array($b, $r)) {
                ++$ans;
            }
        }

        return $ans / 2;
    }

    private function search(array $xmas, array $directions): array
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());

        $r = [];
        foreach ($data as $y => $line) {
            foreach ($line as $x => $value) {
                if ($xmas[0] === $value) {
                    $r = array_merge($r, $this->searchXmas($y, $x, $data, $xmas, $directions));
                }
            }
        }

        return $r;
    }

    private function searchXmas(int $y, int $x, array $grid, array $xmas, array $directions): array
    {
        $results = [];

        foreach ($directions as [$dy, $dx]) {
            $d = 0;
            $nx = $x;
            $ny = $y;

            while (isset($grid[$ny][$nx]) && $grid[$ny][$nx] === $xmas[$d]) {
                if ($d === count($xmas) - 1) {
                    $results[] = [[$y, $x], [$ny, $nx]];
                    break;
                }

                ++$d;
                $nx = $x + $dx * $d;
                $ny = $y + $dy * $d;
            }
        }

        return $results;
    }
}
