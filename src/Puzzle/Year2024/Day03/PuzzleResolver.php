<?php

namespace App\Puzzle\Year2024\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 161;
    protected static int|string $testPart2Expected = 48;

    protected static int|string $part1Expected = 189600467;
    protected static int|string $part2Expected = 107069718;

    public function part1(): int
    {
        $data = $this->getInput()->getArrayData();
        $part = implode('', $data);

        return $this->calculateSum($part);
    }

    public function part2(): int
    {
        $data = $this->getInput()->getArrayData();
        $part = implode('', $data);

        return $this->calculateSum($part, true);
    }

    private function calculateSum(string $input, bool $withState = false): int
    {
        $ans = 0;
        $enabled = true;

        $pattern = $withState
            ? '@mul\((?P<X>\d{1,3}),(?P<Y>\d{1,3})\)|(?P<DO>do\(\))|(?P<DONT>don\'t\(\))@'
            : '/mul\((?P<X>\d{1,3}),(?P<Y>\d{1,3})\)/';

        preg_match_all($pattern, $input, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            if ($withState) {
                if (!empty($match['DO'])) {
                    $enabled = true;
                    continue;
                }

                if (!empty($match['DONT'])) {
                    $enabled = false;
                    continue;
                }
            }

            if (!empty($match['X']) && !empty($match['Y']) && $enabled) {
                $ans += (int) $match['X'] * (int) $match['Y'];
            }
        }

        return $ans;
    }
}
