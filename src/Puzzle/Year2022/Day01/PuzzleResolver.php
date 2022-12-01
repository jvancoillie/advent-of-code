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
        $carry = $this->getCarry();

        return max($carry);
    }

    public function part2()
    {
        $carry = $this->getCarry();

        rsort($carry);

        return $carry[0] + $carry[1] + $carry[2];
    }

    /**
     * @return int[]
     */
    protected function getCarry(): array
    {
        $elf = 0;
        $carry = [$elf => 0];
        $data = explode("\n", $this->getInput()->getData());

        foreach ($data as $cal) {
            if ('' === $cal) {
                ++$elf;
                $carry[$elf] = 0;
            }

            $carry[$elf] += (int) $cal;
        }

        return $carry;
    }
}
