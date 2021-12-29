<?php

namespace App\Puzzle\Year2020\Day18;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 437;
    protected static int|string $testPart2Expected = 1445;

    protected static int|string $part1Expected = 2743012121210;
    protected static int|string $part2Expected = 65658760783597;

    public function part1(): int
    {
        $calculator = new Calculator();
        $total = 0;

        foreach ($this->getInput()->getArrayData() as $input) {
            $total += $calculator->eval($input);
        }

        return $total;
    }

    public function part2(): int
    {
        $calculator = new Calculator();
        $total = 0;

        foreach ($this->getInput()->getArrayData() as $input) {
            $total += $calculator->eval($input, true);
        }

        return $total;
    }
}
