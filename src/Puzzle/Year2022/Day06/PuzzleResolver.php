<?php

namespace App\Puzzle\Year2022\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 7;
    protected static int|string $testPart2Expected = 19;

    protected static int|string $part1Expected = 1833;
    protected static int|string $part2Expected = 3425;

    public function part1()
    {
        return $this->markerStartAt($this->getInput()->getData(), 4);
    }

    public function part2()
    {
        return $this->markerStartAt($this->getInput()->getData(), 14);
    }

    public function markerStartAt(string $string, int $packetLength)
    {
        $i = -1;
        $marker = '';

        while (strlen($marker) !== $packetLength && $i < strlen($string) - $packetLength) {
            $marker = count_chars(substr($string, ++$i, $packetLength), 3);
        }

        return $i + $packetLength;
    }
}
