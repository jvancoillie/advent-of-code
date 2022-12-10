<?php

namespace App\Puzzle\Year2022\Day10;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 13140;
    protected static int|string $testPart2Expected = 'printed ^';

    protected static int|string $part1Expected = 11780;
    protected static int|string $part2Expected = 'printed ^';

    public function part1(): int
    {
        $x = 1;
        $signalStrength = [20 => 0, 60 => 0, 100 => 0, 140 => 0, 180 => 0, 220 => 0];
        $cycle = 0;

        foreach ($this->getInput()->getArrayData() as $entry) {
            if (isset($signalStrength[++$cycle])) {
                $signalStrength[$cycle] = $cycle * $x;
            }

            if (preg_match('/^addx (?P<value>.*)/', $entry, $matches)) {
                if (isset($signalStrength[++$cycle])) {
                    $signalStrength[$cycle] = $cycle * $x;
                }

                $x += (int) $matches['value'];
            }
        }

        return (int) array_sum($signalStrength);
    }

    public function part2(): string
    {
        $x = 1;
        $cycle = 0;
        $crt = [];

        foreach ($this->getInput()->getArrayData() as $entry) {
            $crt[] = in_array(($cycle++) % 40, range($x - 1, $x + 1)) ? '#' : ' ';

            if (preg_match('/^addx (?P<value>.*)/', $entry, $matches)) {
                $crt[] = in_array(($cycle++) % 40, range($x - 1, $x + 1)) ? '#' : ' ';
                $x += (int) $matches['value'];
            }
        }

        foreach (array_map(fn ($e) => implode($e), array_chunk($crt, 40)) as $line) {
            $this->getOutput()->writeln($line);
        }

        return 'printed ^';
    }
}
