<?php

namespace App\Puzzle\Year2016\Day13;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 11;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 82;
    protected static int|string $part2Expected = 138;

    private array $reach;
    private int $favoriteNumber;

    protected function initialize(): void
    {
        $this->reach = ($this->isTestMode()) ? [7, 4] : [31, 39];

        $this->favoriteNumber = $this->getInput()->getData();
    }

    public function part1()
    {
        $maze = new Maze($this->favoriteNumber);

        $start = $maze->createPoint(1, 1);
        $goal = $maze->createPoint($this->reach[0], $this->reach[1]);

        $dijkstra = new Dijkstra($maze);

        $path = $dijkstra->findPath($start, $goal);

        return count($path) - 1;
    }

    public function part2()
    {
        $maze = new Maze($this->favoriteNumber);
        $start = $maze->createPoint(1, 1);
        $count = 0;
        $queue = new \SplQueue();
        $queue->enqueue([$start, 0]);
        $visited = [$start->hash()];

        while (count($queue) > 0) {
            [$node, $distance] = $queue->dequeue();
            if ($distance > 50) {
                continue;
            }

            ++$count;

            foreach ($maze->getNeighbors($node) as $neighbor) {
                if (!in_array($neighbor->hash(), $visited)) {
                    $visited[] = $neighbor->hash();
                    $queue->enqueue([$neighbor, $distance + 1]);
                }
            }
        }

        return $count;
    }
}
