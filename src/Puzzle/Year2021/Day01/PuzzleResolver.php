<?php

namespace App\Puzzle\Year2021\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 7;
    protected static int|string $testPart2Expected = 5;

    protected static int|string $part1Expected = 1696;
    protected static int|string $part2Expected = 1737;

    public function part1()
    {
        return $this->countIncrease(explode("\n", $this->getInput()->getData()), 1);
    }

    public function part2()
    {
        return $this->countIncrease(explode("\n", $this->getInput()->getData()), 3);
    }

    /**
     * @param string[] $data
     *
     * @psalm-param non-empty-list<string> $data
     * @psalm-param 1|3 $size
     */
    private function countIncrease(array $data, int $size): int
    {
        $increase = 0;

        for ($i = 0; $i < count($data) - $size; ++$i) {
            if ($data[$i] < $data[$i + $size]) {
                ++$increase;
            }
        }

        return $increase;
    }
}
