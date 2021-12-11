<?php

namespace App\Puzzle\Year2019\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2019/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 654;
    protected static int|string $testPart2Expected = 966;

    protected static int|string $part1Expected = 3397667;
    protected static int|string $part2Expected = 5093620;

    public function part1(): int
    {
        $ans = 0;

        $data = explode("\n", $this->getInput()->getData());
        foreach ($data as $mass) {
            $ans += floor($mass / 3) - 2;
        }

        return (int) $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = explode("\n", $this->getInput()->getData());
        foreach ($data as $mass) {
            while ($mass > 0) {
                $mass = floor($mass / 3) - 2;
                if ($mass > 0) {
                    $ans += $mass;
                }
            }
        }

        return (int) $ans;
    }
}
