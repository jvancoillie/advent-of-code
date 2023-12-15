<?php

namespace App\Puzzle\Year2023\Day15;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1320;
    protected static int|string $testPart2Expected = 145;

    protected static int|string $part1Expected = 510273;
    protected static int|string $part2Expected = 212449;

    public function part1(): int
    {
        $data = explode(',', $this->getInput()->getData());

        return array_reduce($data, fn ($c, $string) => $c + $this->getHash($string));
    }

    public function part2(): int
    {
        $data = explode(',', $this->getInput()->getData());
        $boxes = array_fill(0, 256, []);

        foreach ($data as $string) {
            $operation = str_contains($string, '-') ? '-' : '=';
            [$label, $number] = explode($operation, $string);

            $hash = $this->getHash($label);

            if ('-' === $operation) {
                unset($boxes[$hash][$label]);
            }

            if ('=' === $operation) {
                $boxes[$hash][$label] = $number;
            }
        }

        return $this->getFocusingPower($boxes);
    }

    private function getHash(string $string): int
    {
        $hash = 0;

        for ($i = 0; $i < strlen($string); ++$i) {
            $hash += ord($string[$i]);
            $hash *= 17;
            $hash %= 256;
        }

        return $hash;
    }

    private function getFocusingPower(array $boxes): int
    {
        $power = 0;

        foreach ($boxes as $n => $box) {
            $boxNumber = $n + 1;
            $slot = 1;
            foreach ($box as $focal) {
                $power += $boxNumber * $slot * $focal;
                ++$slot;
            }
        }

        return $power;
    }
}
