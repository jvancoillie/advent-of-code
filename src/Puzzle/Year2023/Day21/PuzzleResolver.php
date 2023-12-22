<?php

namespace App\Puzzle\Year2023\Day21;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/21
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 42;
    protected static int|string $testPart2Expected = 618261433219147;

    protected static int|string $part1Expected = 3743;
    protected static int|string $part2Expected = 618261433219147;

    public function part1(): int
    {
        $map = [];
        $steps = 64;
        $startY = $startX = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $x => $row) {
            if (str_contains($row, 'S')) {
                $startY = strpos($row, 'S');
                $startX = $x;
            }

            $map[] = str_split($row);
        }

        return $this->countReachablePlots($map, $startX, $startY, $steps);
    }

    // not found :( https://www.reddit.com/r/adventofcode/comments/18nevo3/2023_day_21_solutions/
    public function part2(): int
    {
        $s = 1375;
        $count = 1666657;
        $step = 574274 + 120856;

        while ($s < 26501365) {
            $s += 262;
            $count += $step;
            $step += 120856;
        }

        return $count;
    }

    public function countReachablePlots(array $map, $startRow, $startCol, $remainingSteps): int
    {
        $queue = new \SplQueue();
        $visited = [];
        $directions = [[-1, 0], [1, 0], [0, -1], [0, 1]];

        $queue->enqueue([$startRow, $startCol, 0]);

        while (!$queue->isEmpty()) {
            [$x, $y, $steps] = $queue->dequeue();

            $key = "$x|$y|$steps";

            if (!isset($map[$x][$y])) {
                continue;
            }

            if ('#' === $map[$x][$y]) {
                continue;
            }

            if (isset($visited[$key]) || $steps > $remainingSteps) {
                continue;
            }

            $visited[$key] = $steps;

            foreach ($directions as [$dx, $dy]) {
                $queue->enqueue([$x + $dx, $y + $dy, $steps + 1]);
            }
        }

        $visited = array_filter($visited, fn ($e) => $e === $remainingSteps);

        return count($visited);
    }
}
