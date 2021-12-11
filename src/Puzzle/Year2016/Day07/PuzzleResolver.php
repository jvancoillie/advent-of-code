<?php

namespace App\Puzzle\Year2016\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 3;

    protected static int|string $part1Expected = 105;
    protected static int|string $part2Expected = 258;

    public function part1()
    {
        $ans = 0;

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $parsed = $this->parseLine($line);
            if (!$this->hasABBA($parsed['in']) && $this->hasABBA($parsed['out'])) {
                ++$ans;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $parsed = $this->parseLine($line);
            if ($this->isSSl($parsed)) {
                ++$ans;
            }
        }

        return $ans;
    }

    /**
     * @return (mixed|string)[][]
     *
     * @psalm-return array{out: non-empty-list<mixed|string>, in: list<string>}
     */
    public function parseLine(string $line): array
    {
        $out = $in = [];
        while (false !== $pos = strpos($line, '[')) {
            $out[] = substr($line, 0, $pos);
            $line = substr($line, $pos + 1);
            $pos = strpos($line, ']');
            $in[] = substr($line, 0, $pos);
            $line = substr($line, $pos + 1);
        }

        $out[] = $line;

        return ['out' => $out, 'in' => $in];
    }

    /**
     * @param (mixed|string)[] $strings
     *
     * @psalm-param list<mixed|string> $strings
     */
    public function hasABBA(array $strings): bool
    {
        foreach ($strings as $string) {
            $split = str_split($string);
            for ($i = 0; $i < count($split) - 3; ++$i) {
                if (
                    $split[$i] !== $split[$i + 1] &&
                    $split[$i] === $split[$i + 3] &&
                    $split[$i + 1] === $split[$i + 2]
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param (mixed|string)[][] $ip
     *
     * @psalm-param array{out: non-empty-list<mixed|string>, in: list<string>} $ip
     */
    public function isSSl(array $ip): bool
    {
        //extract all ABA from outside brackets and check if in brackets
        foreach ($ip['out'] as $string) {
            $split = str_split($string);
            for ($i = 0; $i < count($split) - 2; ++$i) {
                if (
                    $split[$i] !== $split[$i + 1] &&
                    $split[$i] === $split[$i + 2]
                ) {
                    $bab = $split[$i + 1].$split[$i].$split[$i + 1];
                    if (array_filter($ip['in'], function ($v) use ($bab) {
                        return str_contains($v, $bab);
                    })) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
