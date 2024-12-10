<?php

namespace App\Puzzle\Year2024\Day10;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 36;
    protected static int|string $testPart2Expected = 81;

    protected static int|string $part1Expected = 659;
    protected static int|string $part2Expected = 1463;

    public function part1(): int
    {
        return $this->solve(false);
    }

    public function part2(): int
    {
        return $this->solve(true);
    }

    private function solve(bool $distinct = false): int
    {
        $ans = 0;
        $data = array_map(fn (string $row) => array_map('intval', str_split($row)), $this->getInput()->getArrayData());
        $starts = $this->getStartPoints($data);

        foreach ($starts as $start) {
            $paths = $this->findPath($start, $data, $distinct);
            $ans += count($paths);
        }

        return $ans;
    }

    private function getStartPoints(array $data): array
    {
        $starts = [];
        foreach ($data as $y => $row) {
            foreach ($row as $x => $value) {
                if (0 === $value) {
                    $starts[] = [$y, $x];
                }
            }
        }

        return $starts;
    }

    private function findPath(array $start, array $grid, bool $distinct = false): array
    {
        $queue = new \SplQueue();
        $key = "{$start[0]}_{$start[1]}";
        $visited = [$key => true];
        $queue->enqueue([$start, [$start], $visited]);
        $paths = [];

        while ($queue->count() > 0) {
            if ($distinct) {
                [$pos, $path, $visited] = $queue->dequeue();
            } else {
                [$pos, $path] = $queue->dequeue();
            }

            [$y, $x] = $pos;

            if (9 === $grid[$y][$x]) {
                $paths[] = $path;
                continue;
            }

            foreach (Grid::$crossDirections as [$dy, $dx]) {
                $ny = $y + $dy;
                $nx = $x + $dx;

                if (isset($grid[$ny][$nx]) && $grid[$ny][$nx] - 1 === $grid[$y][$x]) {
                    $key = "{$ny}_{$nx}";
                    if (isset($visited[$key])) {
                        continue;
                    }

                    $nextPath = $path;
                    $nextPath[] = [$ny, $nx];
                    $visited[$key] = true;

                    if ($distinct) {
                        $queue->enqueue([[$ny, $nx], $nextPath, $visited]);
                    } else {
                        $queue->enqueue([[$ny, $nx], $nextPath, []]);
                    }
                }
            }
        }

        return $paths;
    }
}
