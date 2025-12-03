<?php

namespace App\Puzzle\Year2025\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 357;
    protected static int|string $testPart2Expected = 3121910778619;

    protected static int|string $part1Expected = 17095;
    protected static int|string $part2Expected = 168794698570517;

    public function part1(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $line) {
            $ans += $this->joltage(line: $line, depth: 2);
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();
        foreach ($data as $line) {
            $ans += $this->joltage(line: $line, depth: 12);
        }

        return $ans;
    }

    public function joltage(string $line, int $depth = 2, int $pos = 0, $r = ''): int
    {
        if (0 === $depth) {
            return (int) $r;
        }

        $line = trim($line);
        $l = strlen($line);

        $max = 0;
        $nextPos = $pos + 1;
        for ($i = $pos; $i < ($l - $depth + 1); ++$i) {
            $n = $line[$i];

            if ($n > $max) {
                $max = $n;
                $nextPos = $i + 1;
            }
        }

        return $this->joltage($line, $depth - 1, $nextPos, $r.$max);
    }
}
