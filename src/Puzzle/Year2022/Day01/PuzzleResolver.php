<?php

namespace App\Puzzle\Year2022\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 24000;
    protected static int|string $testPart2Expected = 45000;

    protected static int|string $part1Expected = 71780;
    protected static int|string $part2Expected = 212489;

    public function part1()
    {
        $carry = array_slice($this->getCarry(), 0, 1);

        return array_sum($carry);
    }

    public function part2()
    {
        $carry = array_slice($this->getCarry(), 0, 3);

        return array_sum($carry);
    }

    /**
     * @return int[]
     */
    protected function getCarry(): array
    {
        $data = array_map(fn ($entry) => array_sum(explode("\n", $entry)), explode("\n\n", $this->getInput()->getData()));

        rsort($data);

        return $data;
    }
}
