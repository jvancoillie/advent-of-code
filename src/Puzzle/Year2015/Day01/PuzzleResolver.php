<?php

namespace App\Puzzle\Year2015\Day01;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = -1;
    protected static int|string $testPart2Expected = 5;

    protected static int|string $part1Expected = 74;
    protected static int|string $part2Expected = 1795;

    public function part1()
    {
        $floor = 0;
        foreach (str_split($this->getInput()->getData()) as $data) {
            if ('(' === $data) {
                ++$floor;
            } elseif (')' === $data) {
                --$floor;
            }
        }

        return $floor;
    }

    public function part2()
    {
        $floor = $response = 0;
        foreach (str_split($this->getInput()->getData()) as $key => $data) {
            if ('(' === $data) {
                ++$floor;
            } elseif (')' === $data) {
                --$floor;
            }

            if ($floor < 0) {
                $response = $key + 1;
                break;
            }
        }

        return $response;
    }
}
