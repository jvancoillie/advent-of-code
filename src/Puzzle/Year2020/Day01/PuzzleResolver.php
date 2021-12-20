<?php

namespace App\Puzzle\Year2020\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 514579;
    protected static int|string $testPart2Expected = 241861950;

    protected static int|string $part1Expected = 1019904;
    protected static int|string $part2Expected = 176647680;

    public function part1(): int
    {
        $input = array_map('intval', explode("\n", $this->getInput()->getData()));

        foreach ($input as $first) {
            foreach ($input as $second) {
                if (2020 === $first + $second) {
                    return $first * $second;
                }
            }
        }

        return 0;
    }

    public function part2(): int
    {
        $input = array_map('intval', explode("\n", $this->getInput()->getData()));

        foreach ($input as $first) {
            foreach ($input as $second) {
                foreach ($input as $third) {
                    if (2020 === $first + $second + $third) {
                        return $first * $second * $third;
                    }
                }
            }
        }

        return 0;
    }
}
