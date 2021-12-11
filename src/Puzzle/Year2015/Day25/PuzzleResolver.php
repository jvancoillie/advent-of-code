<?php

namespace App\Puzzle\Year2015\Day25;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/25
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2650453;
    protected static int|string $testPart2Expected = 'Merry christmas';

    protected static int|string $part1Expected = 2650453;
    protected static int|string $part2Expected = 'Merry christmas';

    public function part1()
    {
        preg_match('/^To continue, please consult the code grid in the manual.  Enter the code at row (?<row>\d+), column (?<column>\d+).$/', $this->getInput()->getData(), $matches);
        $targetRow = (int) $matches['row'];
        $targetColumn = (int) $matches['column'];

        $column = $row = $nextRow = 1;
        $multiply = 252533;
        $divide = 33554393;
        $ans = 20151125;

        while ($column !== $targetColumn || $row !== $targetRow) {
            $ans = ($ans * $multiply) % $divide;
            if (1 === $row) {
                $column = 1;
                $row = ++$nextRow;
            } else {
                ++$column;
                --$row;
            }
        }

        return $ans;
    }

    public function part2()
    {
        return 'Merry christmas';
    }
}
