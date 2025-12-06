<?php

namespace App\Puzzle\Year2025\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4277556;
    protected static int|string $testPart2Expected = 3263827;

    protected static int|string $part1Expected = 6343365546996;
    protected static int|string $part2Expected = 11136895955912;

    public function part1(): int
    {
        $data = array_map(fn (string $line): array => preg_split('/\s+/', $line), $this->getInput()->getArrayData());

        $operators = array_pop($data);
        $numbers = array_map(fn (array $r): array => array_map('intval', $r), $data);

        $numberList = [];
        foreach ($operators as $i => $operator) {
            $d = [];
            foreach ($numbers as $number) {
                $d[] = $number[$i];
            }
            $numberList[] = $d;
        }

        return $this->math($numberList, $operators);
    }

    public function part2(): int
    {
        $data = $this->getInput()->getArrayData(false);

        $operators = array_pop($data);
        $numbers = $data;
        [$numbers, $operators] = $this->parse($numbers, $operators);

        return $this->math($numbers, $operators);
    }

    public function parse(array $numbers, string $operators): array
    {
        $ops = str_split($operators);
        $operatorList = [];
        $numberList = [];

        $blockStart = null;

        foreach ($ops as $i => $char) {
            if (' ' !== $char) {
                $operatorList[] = $char;

                if (null === $blockStart) {
                    $blockStart = $i;
                    continue;
                }

                $numberList[] = $this->extractNumbers($numbers, $blockStart, $i);
                $blockStart = $i;
            }
        }

        $numberList[] = $this->extractNumbers($numbers, $blockStart);

        return [$numberList, $operatorList];
    }

    public function extractNumbers(array $numbers, int $start, int $end = null): array
    {
        $tmp = array_map(
            fn ($number) => null === $end ? substr($number, $start) : substr($number, $start, $end - $start - 1),
            $numbers
        );

        $list = [];

        $len = max(array_map('strlen', $tmp));
        for ($x = 0; $x < $len; ++$x) {
            $n = '';
            foreach ($tmp as $number) {
                $n .= $number[$x] ?? '';
            }
            $list[] = (int) $n;
        }

        return $list;
    }

    private function math(array $numbers, array $operators): int
    {
        $result = 0;

        foreach ($operators as $i => $op) {
            $vals = $numbers[$i];
            $result += '*' === $op ? array_product($vals) : array_sum($vals);
        }

        return $result;
    }
}
