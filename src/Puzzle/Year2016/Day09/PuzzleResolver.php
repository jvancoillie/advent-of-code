<?php

namespace App\Puzzle\Year2016\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 57;
    protected static int|string $testPart2Expected = 56;

    protected static int|string $part1Expected = 102239;
    protected static int|string $part2Expected = 10780403063;

    public function part1()
    {
        return $this->decompress($this->getInput()->getData(), false);
    }

    public function part2()
    {
        return $this->decompress($this->getInput()->getData());
    }

    public function decompress(string $sequence, bool $recurse = true): float|int
    {
        $decompressedLength = 0;
        while (false !== $pos = strpos($sequence, '(')) {
            $decompressedLength += strlen(substr($sequence, 0, $pos));

            if (preg_match("/\((\d+)x(\d+)\)(.+)/", $sequence, $matches)) {
                [,$subsequentLength, $repeated, $remaining] = $matches;

                $stringLength = ($recurse) ? $this->decompress(substr($remaining, 0, (int) $subsequentLength)) : strlen(substr($remaining, 0, (int) $subsequentLength));
                $decompressedLength += (int) $repeated * $stringLength;

                $sequence = substr($remaining, (int) $subsequentLength);
            }
        }

        return $decompressedLength + strlen($sequence);
    }
}
