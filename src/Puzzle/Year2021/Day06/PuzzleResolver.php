<?php

namespace App\Puzzle\Year2021\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 5934;
    protected static int|string $testPart2Expected = 26984457539;

    protected static int|string $part1Expected = 353079;
    protected static int|string $part2Expected = 1605400130036;

    public function part1()
    {
        $data = explode(',', $this->getInput()->getData());

        return $this->grow($data, 80);
    }

    public function part2()
    {
        $data = explode(',', $this->getInput()->getData());

        return $this->grow($data, 256);
    }

    public function grow($data, $days): float|int
    {
        $d = array_fill(0, 9, 0);
        foreach (array_count_values($data) as $key => $count) {
            $d[(int) $key] = $count;
        }

        $i = 0;
        while ($i++ < $days) {
            $d[7] += $d[0];
            array_push($d, array_shift($d));
        }

        return array_sum($d);
    }
}
