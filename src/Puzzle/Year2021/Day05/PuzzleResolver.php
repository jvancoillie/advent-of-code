<?php

namespace App\Puzzle\Year2021\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 5;
    protected static int|string $testPart2Expected = 12;

    protected static int|string $part1Expected = 7438;
    protected static int|string $part2Expected = 21406;

    /**
     * @var Segment[]
     */
    private array $segments;

    public function main()
    {
        $this->parseInput();
    }

    public function part1(): int
    {
        return $this->countOverlapping(false);
    }

    public function part2(): int
    {
        return $this->countOverlapping(true);
    }

    private function parseInput(): void
    {
        foreach (explode("\n", $this->getInput()->getData()) as $entry) {
            if (preg_match('/(?<x1>\d+),(?<y1>\d+) -> (?<x2>\d+),(?<y2>\d+)/', $entry, $matches)) {
                $this->segments[] = new Segment($matches['x1'], $matches['y1'], $matches['x2'], $matches['y2']);
            }
        }
    }

    private function countOverlapping(bool $withDiagonals): int
    {
        $grid = [];

        foreach ($this->segments as $segment) {
            foreach ($segment->getPoints($withDiagonals) as [$x, $y]) {
                if (isset($grid[$y][$x])) {
                    ++$grid[$y][$x];
                } else {
                    $grid[$y][$x] = 1;
                }
            }
        }

        // count overlapping vents in grid, x,y > 1
        return array_reduce($grid, function ($carry, $item) { return $carry + count(array_filter($item, function ($e) {return $e > 1; })); });
    }
}
