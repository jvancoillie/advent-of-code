<?php

namespace App\Puzzle\Year2024\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public const OBSTRUCTION = '#';
    public const EMPTY = '.';

    public const DIRECTIONS = [
        '^' => [-1, 0],
        '>' => [0, 1],
        'v' => [1, 0],
        '<' => [0, -1],
    ];

    protected static int|string $testPart1Expected = 41;
    protected static int|string $testPart2Expected = 6;

    protected static int|string $part1Expected = 5239;
    protected static int|string $part2Expected = 1753;
    private array $grid = [];
    private array $initialPosition = [];
    private array $initialDirection = [];

    public function part1(): int
    {
        $data = $this->getInput()->getArrayData();
        $grid = [];
        $guardPosition = [0, 0];
        $direction = [-1, 0];

        foreach ($data as $y => $row) {
            $line = str_split($row);
            foreach ($line as $x => $cell) {
                if (in_array($cell, array_keys(self::DIRECTIONS), true)) {
                    $guardPosition = [$y, $x];
                    $direction = self::DIRECTIONS[$cell];
                    $cell = 'X';
                }
                $grid[$y][$x] = $cell;
            }
        }

        $steps = $this->walk($guardPosition, $direction, $grid);
        $this->grid = $grid;
        $this->initialPosition = $guardPosition;
        $this->initialDirection = $direction;

        return $steps;
    }

    public function part2(): int
    {
        $grid = $this->grid;
        $loopInducingPositions = 0;

        $guardPosition = $this->initialPosition;
        $direction = $this->initialDirection;

        foreach ($grid as $y => $row) {
            foreach ($row as $x => $cell) {
                if ('X' === $cell) {
                    $grid[$y][$x] = self::OBSTRUCTION;

                    if ($this->createsLoop($grid, $guardPosition, $direction)) {
                        ++$loopInducingPositions;
                    }

                    $grid[$y][$x] = 'X';
                }
            }
        }

        return $loopInducingPositions;
    }

    private function walk(array $guardPosition, array $direction, array &$grid, int $step = 1): int
    {
        if (self::EMPTY === $grid[$guardPosition[0]][$guardPosition[1]]) {
            $grid[$guardPosition[0]][$guardPosition[1]] = 'X';
            ++$step;
        }

        $nextPosition = [$guardPosition[0] + $direction[0], $guardPosition[1] + $direction[1]];

        if (!isset($grid[$nextPosition[0]][$nextPosition[1]])) {
            return $step;
        }

        if (self::OBSTRUCTION === $grid[$nextPosition[0]][$nextPosition[1]]) {
            $direction = $this->rotateRight($direction);

            return $this->walk($guardPosition, $direction, $grid, $step);
        }

        return $this->walk($nextPosition, $direction, $grid, $step);
    }

    private function rotateRight(array $direction): array
    {
        return [$direction[1], -$direction[0]];
    }

    private function createsLoop(array $grid, array $guardPosition, array $direction): bool
    {
        $visited = [];

        while (true) {
            if (isset($visited[$guardPosition[0]][$guardPosition[1]][$this->directionKey($direction)])) {
                return true;
            }

            $visited[$guardPosition[0]][$guardPosition[1]][$this->directionKey($direction)] = true;

            $nextPosition = [$guardPosition[0] + $direction[0], $guardPosition[1] + $direction[1]];

            if (!isset($grid[$nextPosition[0]][$nextPosition[1]])) {
                return false;
            }

            if (self::OBSTRUCTION === $grid[$nextPosition[0]][$nextPosition[1]]) {
                $direction = $this->rotateRight($direction);
                continue;
            }

            $guardPosition = $nextPosition;
        }
    }

    private function directionKey(array $direction): string
    {
        return implode(',', $direction);
    }
}
