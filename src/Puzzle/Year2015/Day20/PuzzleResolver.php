<?php

namespace App\Puzzle\Year2015\Day20;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/20
 *
 * Not found by my self
 * @see https://medium.com/@ghaiklor/advent-of-code-2015-explanation-aa9932db6d6f#9795
 *
 * need to run with php -d  memory_limit=2048M bin/console puzzle:resolve --year=2015 --day=20
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 8;
    protected static int|string $testPart2Expected = 8;

    protected static int|string $part1Expected = 776160;
    protected static int|string $part2Expected = 786240;

    public function part1()
    {
        $input = (int) $this->getInput()->getData() / 10;
        $houses = [];
        $houseNumber = $input;

        for ($i = 1; $i < $input; ++$i) {
            for ($j = $i; $j < $input; $j += $i) {
                if (!isset($houses[$j])) {
                    $houses[$j] = 0;
                }
                if (($houses[$j] += $i) >= $input && $j < $houseNumber) {
                    $houseNumber = $j;
                }
            }
        }

        return $houseNumber;
    }

    public function part2()
    {
        $input = (int) $this->getInput()->getData() / 10;
        $houses = [];
        $houseNumber = $input;

        for ($i = 1; $i < $input; ++$i) {
            $visits = 0;
            for ($j = $i; $j < $input; $j += $i) {
                if (!isset($houses[$j])) {
                    $houses[$j] = 11;
                }
                if (($houses[$j] = $houses[$j] + $i * 11) >= $input * 10 && $j < $houseNumber) {
                    $houseNumber = $j;
                }

                ++$visits;
                if (50 === $visits) {
                    break;
                }
            }
        }

        return $houseNumber;
    }
}
