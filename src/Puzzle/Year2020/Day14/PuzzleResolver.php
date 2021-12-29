<?php

namespace App\Puzzle\Year2020\Day14;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 51;
    protected static int|string $testPart2Expected = 208;

    protected static int|string $part1Expected = 10717676595607;
    protected static int|string $part2Expected = 3974538275659;

    private array $program;

    protected function initialize(): void
    {
        foreach ($this->getInput()->getArrayData() as $line) {
            if (preg_match('/^mask\s=\s(?P<mask>.*)$/', $line, $matches, PREG_OFFSET_CAPTURE)) {
                $this->program[] = ['mask', $matches['mask'][0]];
            }

            if (preg_match('/^mem\[(?P<index>\d+)\]\s=\s(?P<value>.*)$/', $line, $matches, PREG_OFFSET_CAPTURE)) {
                $this->program[] = [(int) $matches['index'][0], (int) $matches['value'][0]];
            }
        }
    }

    public function part1(): int
    {
        $mask = null;
        $memory = [];

        foreach ($this->program as [$key, $value]) {
            if ('mask' === $key) {
                $mask = $value;
                continue;
            }

            $memory[$key] = $this->applyMaskValue($value, $mask);
        }

        return array_sum($memory);
    }

    public function part2(): int
    {
        $mask = null;
        $memory = [];

        foreach ($this->program as [$key, $value]) {
            if ('mask' === $key) {
                $mask = $value;
                continue;
            }

            $comb = $this->applyMaskKey($key, $mask);

            foreach ($comb as $keyB) {
                $memory[$keyB] = $value;
            }
        }

        return array_sum($memory);
    }

    private function applyMaskValue($value, $mask): int
    {
        $str = str_pad(decbin($value), 36, '0', STR_PAD_LEFT);

        for ($i = strlen($str) - 1; $i >= 0; --$i) {
            if ('0' === $mask[$i] || '1' === $mask[$i]) {
                if ($str[$i] !== $mask[$i]) {
                    $str[$i] = $mask[$i];
                }
            }
        }

        return bindec($str);
    }

    private function applyMaskKey($value, $mask): array
    {
        $str = str_pad(decbin($value), 36, '0', STR_PAD_LEFT);

        for ($i = strlen($str) - 1; $i >= 0; --$i) {
            if ('1' === $mask[$i] || 'X' === $mask[$i]) {
                $str[$i] = $mask[$i];
            }
        }

        return $this->combinations($str, strlen($str) - 1);
    }

    private function combinations($str, $index): array
    {
        for ($i = $index; $i >= 0; --$i) {
            if ('X' === $str[$i]) {
                $tmpA = $str;
                $tmpB = $str;
                $tmpA[$i] = '1';
                $tmpB[$i] = '0';
                $with1 = $this->combinations($tmpA, $i - 1);
                $with0 = $this->combinations($tmpB, $i - 1);

                return array_merge($with0, $with1);
            }
        }

        return [bindec($str)];
    }
}
