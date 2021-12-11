<?php

namespace App\Puzzle\Year2015\Day02;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 101;
    protected static int|string $testPart2Expected = 48;

    protected static int|string $part1Expected = 1588178;
    protected static int|string $part2Expected = 3783758;

    /**
     * 2*l*w + 2*w*h + 2*h*l.
     * l = 0
     * w = 1
     * h = 2.
     */
    public function part1()
    {
        $squareFeet = 0;
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $entry = array_map('intval', explode('x', $line));
            $surfaces['lw'] = $entry[0] * $entry[1];
            $surfaces['wh'] = $entry[1] * $entry[2];
            $surfaces['hl'] = $entry[0] * $entry[2];

            $squareFeet += 2 * $surfaces['lw'];
            $squareFeet += 2 * $surfaces['wh'];
            $squareFeet += 2 * $surfaces['hl'];
            $squareFeet += min($surfaces);
        }

        return $squareFeet;
    }

    public function part2()
    {
        $squareFeet = 0;
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $entry = array_map('intval', explode('x', $line));
            sort($entry);
            $min1 = $entry[0];
            $min2 = $entry[1];
            $squareFeet += $min1 + $min1 + $min2 + $min2;
            $squareFeet += $entry[0] * $entry[1] * $entry[2];
        }

        return $squareFeet;
    }
}
