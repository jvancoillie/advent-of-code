<?php

namespace App\Puzzle\Year2023\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 209;
    protected static int|string $testPart2Expected = 281;

    protected static int|string $part1Expected = 53651;
    protected static int|string $part2Expected = 53894;

    public function part1()
    {
        $ans = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $line) {
            $ans += $this->calibrate('/(?=(\d))/', $line);
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $line) {
            $ans += $this->calibrate('/(?=(one|two|three|four|five|six|seven|eight|nine|\d))/', $line);
        }

        return $ans;
    }

    private function calibrate(string $pattern, string $data): int
    {
        $digitMap = [
            'one' => 1,
            'two' => 2,
            'three' => 3,
            'four' => 4,
            'five' => 5,
            'six' => 6,
            'seven' => 7,
            'eight' => 8,
            'nine' => 9,
        ];

        preg_match_all($pattern, $data, $matches);
        $first = reset($matches[1]);
        $last = end($matches[1]);

        return sprintf('%d%d', $digitMap[$first] ?? $first, $digitMap[$last] ?? $last);
    }
}
