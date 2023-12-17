<?php

namespace App\Puzzle\Year2023\Day17;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 102;
    protected static int|string $testPart2Expected = 94;

    protected static int|string $part1Expected = 928;
    protected static int|string $part2Expected = 1104;

    public const DIRECTIONS = [
        '>' => [
            '^' => [-1, 0],
            'v' => [1, 0],
            '>' => [0, 1],
        ],
        '<' => [
            '^' => [-1, 0],
            '<' => [0, -1],
            'v' => [1, 0],
        ],
        '^' => [
            '^' => [-1, 0],
            '<' => [0, -1],
            '>' => [0, 1],
        ],
        'v' => [
            '<' => [0, -1],
            '>' => [0, 1],
            'v' => [1, 0],
        ],
    ];

    public function part1(): int
    {
        return $this->heat(
            array_map('str_split', $this->getInput()->getArrayData()),
            [[[0, 0], 'v', 0, 0], [[0, 0], '>', 0, 0]],
            fn (int $walk, string $nextDirection, string $direction) => 3 === $walk && $nextDirection === $direction
        );
    }

    public function part2(): int
    {
        return $this->heat(
            array_map('str_split', $this->getInput()->getArrayData()),
            [[[0, 0], 'v', 0, 0], [[0, 0], '>', 0, 0]],
            fn (int $walk, string $nextDirection, string $direction) => (10 === $walk && $nextDirection === $direction) || ($walk < 4 && $nextDirection !== $direction)
        );
    }

    public function heat(array $grid, array $starts, callable $breakCondition): int
    {
        $queue = new \SplPriorityQueue();
        foreach ($starts as $start) {
            $queue->insert($start, 0);
        }

        $endX = count($grid) - 1;
        $endY = count($grid[0]) - 1;
        $min = INF;
        $visited = [];

        while (!$queue->isEmpty()) {
            [[$x, $y] , $direction, $walk, $heat] = $queue->extract();

            if ($x === $endX && $y === $endY) {
                $min = min($min, $heat);
            }

            if (isset($visited["$x|$y|$direction|$walk"])) {
                continue;
            }

            $visited["$x|$y|$direction|$walk"] = $heat;

            foreach (self::DIRECTIONS[$direction] as $nextDirection => $move) {
                $nx = $x + $move[0];
                $ny = $y + $move[1];

                if (!isset($grid[$nx][$ny])) {
                    continue;
                }

                $nextWalk = $nextDirection === $direction ? $walk + 1 : 1;
                $nextHeat = $heat + $grid[$nx][$ny];

                if ($breakCondition($walk, $nextDirection, $direction)) {
                    continue;
                }

                $queue->insert([[$nx, $ny], $nextDirection, $nextWalk, $nextHeat], -$heat);
            }
        }

        return $min;
    }
}
