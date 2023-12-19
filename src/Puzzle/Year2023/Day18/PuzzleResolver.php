<?php

namespace App\Puzzle\Year2023\Day18;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 62;
    protected static int|string $testPart2Expected = 952408144115;

    protected static int|string $part1Expected = 48652;
    protected static int|string $part2Expected = 45757884535661;

    public function part1(): int
    {
        $data = $this->getInput()->getArrayData();

        [$p, $points] = $this->getPoints($data);

        $u = $this->shoelaceFormula($points);

        return $u + $p / 2 + 1;
    }

    public function part2(): int
    {
        $data = $this->getInput()->getArrayData();

        [$p, $points] = $this->getPoints($data, true);

        $u = $this->shoelaceFormula($points);

        return $u + $p / 2 + 1;
    }

    private function getPoints(array $data, $withHex = false): array
    {
        $directions = ['U' => [-1, 0], 'D' => [1, 0], 'L' => [0, -1], 'R' => [0, 1]];
        $parseDirection = ['R', 'D', 'L', 'U'];
        $points = [[0, 0]];
        $x = 0;
        $y = 0;
        $p = 0;

        foreach ($data as $line) {
            [$direction, $length, $color] = explode(' ', $line);

            if ($withHex) {
                $direction = $parseDirection[(int) substr($color, -2, 1)];
                $length = hexdec(substr($color, 2, -2));
            }

            $x += $length * $directions[$direction][0];
            $y += $length * $directions[$direction][1];
            $p += abs($length * $directions[$direction][1] + $length * $directions[$direction][0]);
            $points[] = [$x, $y];
        }

        return [$p, $points];
    }

    public function shoelaceFormula($points): int
    {
        $n = count($points);
        $area = 0;

        for ($i = 0; $i < $n - 1; ++$i) {
            $area += ($points[$i][0] * $points[$i + 1][1]) - ($points[$i + 1][0] * $points[$i][1]);
        }

        $area += ($points[$n - 1][0] * $points[0][1]) - ($points[0][0] * $points[$n - 1][1]);

        return abs($area) / 2;
    }
}
