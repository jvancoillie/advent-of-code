<?php

namespace App\Puzzle\Year2022\Day04;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 4;

    protected static int|string $part1Expected = 534;
    protected static int|string $part2Expected = 841;

    public function part1(): int
    {
        $ans = 0;

        $data = $this->getData();

        foreach ($data as [$rangeA, $rangeB]) {
            $merged = array_merge($rangeA, $rangeB);
            if (count($rangeA) === count(array_unique($merged)) || count($rangeB) === count(array_unique($merged))) {
                ++$ans;
            }
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = $this->getData();

        foreach ($data as $ranges) {
            $merged = array_merge(...$ranges);
            if (count($merged) !== count(array_unique($merged))) {
                ++$ans;
            }
        }

        return $ans;
    }

    /**
     * Transform input data to formatted array.
     *
     * 2-4,6-8
     * 2-3,4-5
     * 5-7,7-9
     *
     * gives
     *
     *  [
     *      [[2,3,4],[6,7,8]],
     *      [[2,3],[4,5]],
     *      [[5,6,7],[7,8,9]],
     *  ]
     */
    protected function getData(): array
    {
        return array_map(function ($e) {
            return array_map(
                fn ($range) => range($range[0], $range[1]),
                array_map(
                    fn ($section) => explode('-', $section),
                    explode(',', $e)
                )
            );
        }, explode("\n", $this->getInput()->getData()));
    }
}
