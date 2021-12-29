<?php

namespace App\Puzzle\Year2016\Day14;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 22728;
    protected static int|string $testPart2Expected = 20183;

    protected static int|string $part1Expected = 23769;
    protected static int|string $part2Expected = 20606;

    private array $memo;

    public function part1(): int
    {
        $salt = $this->getInput()->getData();
        $count = 64;
        $i = 0;
        while (true) {
            $hash = md5(sprintf('%s%s', $salt, $i));
            if (false !== $matches = $this->isValidHash($hash)) {
                $pattern = str_repeat($matches[0][0][0], 5);
                if ($this->isNext1000Valid($i, $salt, $pattern)) {
                    --$count;
                    if (0 === $count) {
                        return $i;
                    }
                }
            }
            ++$i;
        }
    }

    public function part2(): int
    {
        $salt = $this->getInput()->getData();
        $i = 26;
        $count = 64;
        while (true) {
            $string = sprintf('%s%s', $salt, $i);
            $hash = $this->createStretching($string);
            if (false !== $matches = $this->isValidHash($hash)) {
                $pattern = str_repeat($matches[0][0][0], 5);
                if ($this->isNext1000Valid($i, $salt, $pattern, true)) {
                    --$count;
                    if (0 === $count) {
                        return $i;
                    }
                }
            }
            ++$i;
        }
    }

    private function isValidHash(string $hash): false|array
    {
        if (preg_match_all('/((.)\2\2)/', $hash, $matches) > 0) {
            return $matches;
        }

        return false;
    }

    private function isNext1000Valid($number, $salt, $pattern, $stretching = false)
    {
        for ($i = 1; $i <= 1000; ++$i) {
            if ($stretching) {
                $string = sprintf('%s%s', $salt, $i + $number);
                $hash = $this->createStretching($string);
            } else {
                $hash = md5(sprintf('%s%s', $salt, $i + $number));
            }

            if (str_contains($hash, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function createStretching($string): string
    {
        if (isset($this->memo[$string])) {
            return $this->memo[$string];
        }

        $s = $string;

        for ($i = 0; $i <= 2016; ++$i) {
            $s = md5($s);
        }

        $this->memo[$string] = $s;

        return $s;
    }
}
