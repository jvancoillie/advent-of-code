<?php

namespace App\Puzzle\Year2020\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 1124361034;
    protected static int|string $part2Expected = 129444555;
    private array $numbers;

    protected function initialize(): void
    {
        $this->numbers = array_map('intval', $this->getInput()->getArrayData());
    }

    public function part1(): int
    {
        return $this->solve($this->numbers, false);
    }

    public function part2(): int
    {
        return $this->solve($this->numbers, true);
    }

    private function solve(array $numbers, bool $xmas): int
    {
        $preambleLength = 25;

        $result = 0;

        for ($i = 0; $i < count($numbers); ++$i) {
            if ($i < $preambleLength) {
                continue;
            }

            $preamble = array_slice($numbers, $i - $preambleLength, $preambleLength);

            if (!$this->isValid($numbers[$i], $preamble)) {
                if ($xmas) {
                    $result = $this->xmas($numbers[$i], $numbers);
                } else {
                    $result = $numbers[$i];
                }
                break;
            }
        }

        return $result;
    }

    private function isValid($number, $preamble): bool
    {
        foreach ($preamble as $n1) {
            foreach ($preamble as $n2) {
                if ($n1 === $n2) {
                    continue;
                }

                if ($n1 + $n2 === $number) {
                    return true;
                }
            }
        }

        return false;
    }

    private function xmas($number, $numbers): int
    {
        $list = 0;
        for ($i = 0; $i < count($numbers); ++$i) {
            $sum = 0;
            for ($j = $i; $j < count($numbers); ++$j) {
                $sum += $numbers[$j];

                if ($sum > $number) {
                    break;
                }

                if ($sum === $number) {
                    $list = array_slice($numbers, $i, $j - $i + 1);
                    break 2;
                }
            }
        }

        return ($list) ? max($list) + min($list) : 0;
    }
}
