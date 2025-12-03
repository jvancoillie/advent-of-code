<?php

namespace App\Puzzle\Year2025\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1227775554;
    protected static int|string $testPart2Expected = 4174379265;

    protected static int|string $part1Expected = 40055209690;
    protected static int|string $part2Expected = 50857215650;

    public function part1()
    {
        $ans = 0;
        $data = $this->parseData();
        foreach ($data as $entry) {
            foreach (range($entry[0], $entry[1]) as $number) {
                if ($this->isValid($number)) {
                    continue;
                }
                $ans += $number;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        $data = $this->parseData();
        foreach ($data as $entry) {
            foreach (range($entry[0], $entry[1]) as $number) {
                if ($this->isValid($number, true)) {
                    continue;
                }
                $ans += $number;
            }
        }

        return $ans;
    }

    private function parseData(): array
    {
        $data = $this->getInput()->getData();

        return array_map(fn ($entry) => array_map('intval', explode('-', $entry)), explode(',', $data));
    }

    private function isValid(mixed $number, bool $isPart2 = false): bool
    {
        $len = strlen((string) $number);
        if (!$isPart2 && 0 !== $len % 2) {
            return true;
        }

        if (!$isPart2) {
            $split = array_map('intval', str_split($number, $len / 2));

            return count(array_unique($split)) > 1;
        }

        for ($i = 1; $i < $len; ++$i) {
            if (0 !== $len % $i) {
                continue;
            }

            $split = array_map('intval', str_split($number, $i));
            if (1 === count(array_unique($split))) {
                return false;
            }
        }

        return true;
    }
}
