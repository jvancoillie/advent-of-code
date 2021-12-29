<?php

namespace App\Puzzle\Year2020\Day15;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 436;
    protected static int|string $testPart2Expected = 175594;

    protected static int|string $part1Expected = 319;
    protected static int|string $part2Expected = 2424;

    public function part1(): int
    {
        $data = explode(',', $this->getInput()->getData());

        return $this->play($data, 2020);
    }

    public function part2(): int
    {
        $data = explode(',', $this->getInput()->getData());

        return $this->play($data, 30000000);
    }

    private function play($numbers, $turn): int
    {
        foreach ($numbers as $key => $num) {
            $played[(int) $num] = $key + 1;
        }

        $nextValue = 0;

        for ($i = count($numbers) + 1; $i < $turn; ++$i) {
            if (isset($played[$nextValue])) {
                $offset = $i - $played[$nextValue];
                $played[$nextValue] = $i;
                $nextValue = $offset;
            } else {
                $played[$nextValue] = $i;
                $nextValue = 0;
            }
        }

        return $nextValue;
    }
}
