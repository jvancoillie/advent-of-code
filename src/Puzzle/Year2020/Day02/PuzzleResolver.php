<?php

namespace App\Puzzle\Year2020\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 1;

    protected static int|string $part1Expected = 398;
    protected static int|string $part2Expected = 562;

    public function part1(): int
    {
        $correct = 0;

        foreach ($this->getInput()->getArrayData() as $line) {
            [$range, $letter, $password] = explode(' ', $line);
            [$min, $max] = explode('-', $range);

            $char = substr($letter, 0, -1);

            $count = substr_count($password, $char);

            if ($count >= $min && $count <= $max) {
                ++$correct;
            }
        }

        return $correct;
    }

    public function part2(): int
    {
        $correct = 0;

        foreach ($this->getInput()->getArrayData() as $line) {
            [$range, $letter, $password] = explode(' ', $line);
            [$min, $max] = array_map('intval', explode('-', $range));

            $char = substr($letter, 0, -1);

            if (($password[$min - 1] === $char && $password[$max - 1] !== $char) || ($password[$min - 1] !== $char && $password[$max - 1] === $char)) {
                ++$correct;
            }
        }

        return $correct;
    }
}
