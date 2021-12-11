<?php

namespace App\Puzzle\Year2021\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 150;
    protected static int|string $testPart2Expected = 900;

    protected static int|string $part1Expected = 1694130;
    protected static int|string $part2Expected = 1698850445;

    public function part1()
    {
        return $this->navigate(explode("\n", $this->getInput()->getData()));
    }

    public function part2()
    {
        return $this->navigateWithAim(explode("\n", $this->getInput()->getData()));
    }

    private function navigate(array $data): int
    {
        $depth = $horizontal = 0;
        foreach ($data as $moves) {
            [$action, $dist] = explode(' ', $moves);
            switch ($action) {
                case 'forward':
                    $horizontal += (int) $dist;
                    break;
                case 'down':
                    $depth += (int) $dist;
                    break;
                case 'up':
                    $depth -= (int) $dist;
                    break;
            }
        }

        return $depth * $horizontal;
    }

    private function navigateWithAim(array $data): int
    {
        $depth = $horizontal = $aim = 0;
        foreach ($data as $moves) {
            [$action, $dist] = explode(' ', $moves);
            switch ($action) {
                case 'forward':
                    $horizontal += (int) $dist;
                    $depth += $aim * (int) $dist;
                    break;
                case 'down':
                    $aim += (int) $dist;
                    break;
                case 'up':
                    $aim -= (int) $dist;
                    break;
            }
        }

        return $depth * $horizontal;
    }
}
