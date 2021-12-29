<?php

namespace App\Puzzle\Year2020\Day12;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 25;
    protected static int|string $testPart2Expected = 286;

    protected static int|string $part1Expected = 2228;
    protected static int|string $part2Expected = 42908;

    private Navigation $navigation;

    protected function initialize(): void
    {
        $instructions = array_map(fn ($line) => [substr($line, 0, 1), substr($line, 1)], $this->getInput()->getArrayData());

        $this->navigation = new Navigation($instructions);
    }

    public function part1(): int
    {
        return $this->navigation->navigate();
    }

    public function part2(): int
    {
        return $this->navigation->navigate(true);
    }
}
