<?php

namespace App\Puzzle\Year2021\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 37;
    protected static int|string $testPart2Expected = 168;

    protected static int|string $part1Expected = 337833;
    protected static int|string $part2Expected = 96678050;

    public function part1()
    {
        return $this->cheapestFuelToAlign(explode(',', $this->getInput()->getData()));
    }

    public function part2()
    {
        return $this->cheapestFuelToAlign(explode(',', $this->getInput()->getData()), true);
    }

    public function cheapestFuelToAlign($data, $expensive = false): float|int
    {
        $max = max($data);
        $fuels = [];

        for ($i = 0; $i < $max; ++$i) {
            $fuels[] = array_reduce($data, function ($carry, $pos) use ($i, $expensive) {
                return $carry + $this->spend(abs($pos - $i), $expensive);
            });
        }

        return min($fuels);
    }

    public function spend($n, $expensive): int
    {
        return ($expensive) ? $n * (1 + $n) / 2 : $n;
    }
}
