<?php

namespace App\Puzzle\Year2022\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 157;
    protected static int|string $testPart2Expected = 70;

    protected static int|string $part1Expected = 7553;
    protected static int|string $part2Expected = 2758;

    public function part1(): int
    {
        return $this->getReduce(
            array_map(
                fn ($e) => [str_split(substr($e, 0, strlen($e) / 2)), str_split(substr($e, strlen($e) / 2))],
                $this->getInput()->getArrayData()
            )
        );
    }

    public function part2(): int
    {
        return $this->getReduce(
            array_chunk(
                array_map(
                    fn ($e) => str_split($e),
                    $this->getInput()->getArrayData()
                ), 3)
        );
    }

    protected function getReduce(array $data): int
    {
        return array_reduce($data, function (int $carry, array $items) {
            $u = array_intersect(...$items);
            $l = reset($u);
            $carry += ord($l) - (ctype_upper($l) ? 38 : 96);

            return $carry;
        }, 0);
    }
}
