<?php

namespace App\Puzzle\Year2023\Day16;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 46;
    protected static int|string $testPart2Expected = 51;

    protected static int|string $part1Expected = 7498;
    protected static int|string $part2Expected = 7846;

    public const RIGHT = '>';
    public const LEFT = '<';
    public const UP = '^';
    public const DOWN = 'v';

    public function part1(): int
    {
        $grid = $this->parse($this->getInput()->getArrayData());

        return $this->energized($grid, [0, 0], '>');
    }

    public function part2()
    {
        $grid = $this->parse($this->getInput()->getArrayData());
        $maxX = count($grid) - 1;
        $maxY = count($grid[0]) - 1;
        $max = 0;

        foreach ($grid as $x => $row) {
            if (0 === $x || $x === $maxX) {
                foreach ($row as $y => $cell) {
                    $max = max($max, $this->energized($grid, [$x, $y], $x > 0 ? self::UP : self::DOWN));
                }
            }
            $max = max($max, $this->energized($grid, [$x, 0], self::RIGHT));
            $max = max($max, $this->energized($grid, [$x, $maxY], self::LEFT));
        }

        return $max;
    }

    public function parse(array $data): array
    {
        return array_map('str_split', $data);
    }

    public function energized($grid, $pos, $direction): int
    {
        $queue = new \SplQueue();

        $queue->enqueue([$pos, $direction]);
        $visited = [];

        while (!$queue->isEmpty()) {
            [[$x, $y] , $direction] = $queue->dequeue();

            if (!isset($grid[$x][$y])) {
                continue;
            }

            if (isset($visited["$x|$y"]) && in_array($direction, $visited["$x|$y"])) {
                continue;
            }

            $visited["$x|$y"] ??= [];
            $visited["$x|$y"][] = $direction;
            $nextDirections = $this->getNextDirections($grid[$x][$y], $direction);

            foreach ($nextDirections as $nextDirection) {
                $nextPos = $this->getNextPos([$x, $y], $nextDirection);
                $queue->enqueue([$nextPos, $nextDirection]);
            }
        }

        return count($visited);
    }

    public function getNextPos(array $pos, string $direction): array
    {
        [$x, $y] = $pos;

        switch ($direction) {
            case '>': $y++;
                break;
            case '<': $y--;
                break;
            case '^': $x--;
                break;
            case 'v': $x++;
                break;
        }

        return [$x, $y];
    }

    public function getNextDirections($currentCell, $direction): array
    {
        $rules = [
            '|' => [
                self::RIGHT => [self::DOWN, self::UP],
                self::LEFT => [self::DOWN, self::UP]],
            '-' => [
                self::DOWN => [self::RIGHT, self::LEFT],
                self::UP => [self::RIGHT, self::LEFT]],
            '\\' => [
                self::RIGHT => [self::DOWN],
                self::LEFT => [self::UP],
                self::UP => [self::LEFT],
                self::DOWN => [self::RIGHT],
            ],
            '/' => [
                self::RIGHT => [self::UP],
                self::LEFT => [self::DOWN],
                self::UP => [self::RIGHT],
                self::DOWN => [self::LEFT],
            ],
        ];

        return $rules[$currentCell][$direction] ?? [$direction];
    }
}
