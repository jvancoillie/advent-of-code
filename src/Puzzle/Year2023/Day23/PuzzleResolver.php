<?php

namespace App\Puzzle\Year2023\Day23;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/23
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 94;
    protected static int|string $testPart2Expected = 154;

    protected static int|string $part1Expected = 2402;
    protected static int|string $part2Expected = 6450;

    public const DIRECTIONS = [
        '>' => [0, 1],
        '<' => [0, -1],
        '^' => [-1, 0],
        'v' => [1, 0],
    ];

    public function part1()
    {
        $data = $this->getInput()->getArrayData();
        [$grid, $points] = $this->createGrid($data);

        return $this->walk($points, $grid);
    }

    public function part2()
    {
        $data = $this->getInput()->getArrayData();
        [$grid, $points] = $this->createGrid($data);

        return $this->walk($points, $grid, true);
    }

    public function createGrid(array $data): array
    {
        $points = [];
        $grid = [];
        foreach ($data as $i => $line) {
            if (0 === $i || array_key_last($data) === $i) {
                $p = strpos($line, '.');
                $points[] = [$i, $p];
            }
            $grid[] = str_split($line);
        }

        return [$grid, $points];
    }

    private function walk(array $points, array $grid, $climbing = false)
    {
        [$start, $end] = $points;

        $queue = new \SplQueue();
        $queue->enqueue([$start[0], $start[1], []]);
        $maxLength = 0;

        while (!$queue->isEmpty()) {
            [$x, $y, $visited] = $queue->dequeue();

            if ([$x, $y] === $end) {
                $maxLength = max($maxLength, count($visited));
                continue;
            }
            $key = "$x|$y";
            if (isset($visited[$key])) {
                continue;
            }

            $visited[$key] = $key;

            if (!$climbing) {
                if ('.' !== $grid[$x][$y]) {
                    [$dx, $dy] = self::DIRECTIONS[$grid[$x][$y]];
                    $nx = $x + $dx;
                    $ny = $y + $dy;
                    if (isset($grid[$nx][$ny]) && '#' !== $grid[$nx][$ny]) {
                        $queue->enqueue([$nx, $ny, $visited]);
                    }
                    continue;
                }
            }

            foreach (self::DIRECTIONS as [$dx, $dy]) {
                $nx = $x + $dx;
                $ny = $y + $dy;
                if (isset($grid[$nx][$ny]) && '#' !== $grid[$nx][$ny]) {
                    $queue->enqueue([$nx, $ny, $visited]);
                }
            }
        }

        return $maxLength;
    }
}
