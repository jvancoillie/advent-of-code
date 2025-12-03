<?php

namespace App\Puzzle\Year2025\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3;
    protected static int|string $testPart2Expected = 6;

    protected static int|string $part1Expected = 1191;
    protected static int|string $part2Expected = 6858;

    public function part1()
    {
        return $this->resolve();
    }

    public function part2()
    {
        return $this->resolve(true);
    }

    public function resolve($hex = false): int
    {
        $ans = 50;
        $res = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $part) {
            $prev = $ans;
            preg_match('/^(L|R)(\d+)/', $part, $matches);
            $point = 0;
            $dir = $matches[1];
            $value = (int) $matches[2];
            $r = intdiv($value, 100);
            $n = $value % 100;

            switch ($dir) {
                case 'L':
                    $ans -= $n;

                    if ($ans < 0) {
                        $ans += 100;
                        if (0 !== $prev) {
                            ++$point;
                        }
                    }
                    break;
                case 'R':
                    $ans += $n;

                    if (100 === $ans) {
                        $ans = 0;
                    } elseif ($ans > 100) {
                        $ans -= 100;
                        if ($hex) {
                            ++$point;
                        }
                    }
                    break;
            }

            if (0 === $ans) {
                ++$res;
            }

            if ($hex) {
                $point += $r;
                $res += $point;
            }
        }

        return $res;
    }
}
