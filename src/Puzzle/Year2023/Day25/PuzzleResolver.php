<?php

namespace App\Puzzle\Year2023\Day25;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/25
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 54;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 543036;
    protected static int|string $part2Expected = 0;

    public function part1()
    {
        // python is so cheated, nothing to do, see PuzzleResolver.py  !!!
        // https://networkx.org/documentation/stable/install.html

        return 'run `python3.9 PuzzleResolver.py`, see py file';
    }

    public function part2()
    {
        return 0;
    }
}
