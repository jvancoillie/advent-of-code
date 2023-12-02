<?php

namespace App\Puzzle\Year2016\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1985;
    protected static int|string $testPart2Expected = '5DB3';

    protected static int|string $part1Expected = 56855;
    protected static int|string $part2Expected = 'B3C27';

    public function part1()
    {
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $instruction[] = str_split($line);
        }

        $code = $this->getCode(
            $instruction,
            [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8, 9],
            ],
            1,
            1
        );

        return (int) implode('', $code);
    }

    public function part2()
    {
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $instruction[] = str_split($line);
        }

        $code = $this->getCode(
            $instruction,
            [
                [2 => 1],
                [1 => 2, 2 => 3, 3 => 4],
                [0 => 5, 1 => 6, 2 => 7, 3 => 8, 4 => 9],
                [1 => 'A', 2 => 'B', 3 => 'C'],
                [2 => 'D'],
            ],
            2,
            0
        );

        return implode('', $code);
    }

    /**
     * @param (mixed|string[])[] $insctructions
     * @param (int|string)[][]   $pad
     *
     * @psalm-param array<list<string>|mixed> $insctructions
     * @psalm-param array{0: array{0?: 1, 1?: 2, 2: 1|3}, 1: array{0?: 4, 1: 2|5, 2: 3|6, 3?: 4}, 2: array{0: 5|7, 1: 6|8, 2: 7|9, 3?: 8, 4?: 9}, 3?: array{1: 'A', 2: 'B', 3: 'C'}, 4?: array{2: 'D'}} $pad
     * @psalm-param 1|2 $y
     * @psalm-param 0|1 $x
     *
     * @psalm-return list<mixed>
     */
    private function getCode(array $insctructions, array $pad, int $y, int $x): array
    {
        $code = [];
        foreach ($insctructions as $directions) {
            foreach ($directions as $direction) {
                $nx = $x;
                $ny = $y;
                switch ($direction) {
                    case 'U':
                        $ny--;
                        break;
                    case 'D':
                        $ny++;
                        break;
                    case 'R':
                        $nx++;
                        break;
                    case 'L':
                        $nx--;
                        break;
                }
                if (isset($pad[$ny][$nx])) {
                    $y = $ny;
                    $x = $nx;
                }
            }
            $code[] = $pad[$y][$x];
        }

        return $code;
    }
}
