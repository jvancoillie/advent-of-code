<?php

namespace App\Puzzle\Year2024\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 11;
    protected static int|string $testPart2Expected = 31;

    protected static int|string $part1Expected = 2344935;
    protected static int|string $part2Expected = 27647262;

    public function part1()
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();
        $listA = $listB = [];

        foreach ($data as $entry) {
            [$a, $b] = preg_split('@\s+@', $entry);
            $listA[] = $a;
            $listB[] = $b;
        }

        sort($listA);
        sort($listB);

        for ($i = 0; $i < count($listA); ++$i) {
            $dist = abs($listA[$i] - $listB[$i]);

            $ans += $dist;
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();
        $listA = $listB = [];

        foreach ($data as $entry) {
            [$a, $b] = preg_split('@\s+@', $entry);
            $listA[] = $a;
            $listB[] = $b;
        }

        sort($listA);
        sort($listB);

        for ($i = 0; $i < count($listA); ++$i) {
            $key = $listA[$i];

            $count = count(array_filter($listB, fn ($v) => $v === $key));

            $ans += $key * $count;
        }

        return $ans;
    }
}
