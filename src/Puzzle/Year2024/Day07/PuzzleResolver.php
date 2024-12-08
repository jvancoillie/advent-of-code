<?php

namespace App\Puzzle\Year2024\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3749;
    protected static int|string $testPart2Expected = 11387;

    protected static int|string $part1Expected = 7579994664753;
    protected static int|string $part2Expected = 438027111276610;

    public function part1(): int
    {
        return $this->calculate(false);
    }

    public function part2(): int
    {
        return $this->calculate(true);
    }

    private function calculate(bool $thirdOperator): int
    {
        $ans = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $part) {
            [$r, $n] = explode(':', $part);
            $numbers = explode(' ', trim($n));

            if ($this->evaluate((int) $r, $numbers, 0, $thirdOperator)) {
                $ans += (int) $r;
            }
        }

        return $ans;
    }

    private function evaluate(int $r, array $numbers, int $op = 0, bool $thirdOperator = false): bool
    {
        if (0 === count($numbers)) {
            return $op === $r;
        }

        $n = (int) array_shift($numbers);

        $operations = [
            fn ($op, $n) => $op + $n, // +
            fn ($op, $n) => $op * $n, // *
        ];

        if ($thirdOperator) {
            $operations[] = fn ($op, $n) => (int) ($op.$n); // ||
        }

        foreach ($operations as $operation) {
            if ($this->evaluate($r, $numbers, $operation($op, $n), $thirdOperator)) {
                return true;
            }
        }

        return false;
    }
}
