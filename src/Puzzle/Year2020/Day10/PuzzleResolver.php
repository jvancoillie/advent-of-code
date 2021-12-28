<?php

namespace App\Puzzle\Year2020\Day10;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 35;
    protected static int|string $testPart2Expected = 8;

    protected static int|string $part1Expected = 2210;
    protected static int|string $part2Expected = 7086739046912;

    private array $adapters = [];
    private array $counts = [];

    protected function initialize(): void
    {
        $this->adapters = array_map('intval', $this->getInput()->getArrayData());
        array_unshift($this->adapters, 0);
        sort($this->adapters);
    }

    public function part1(): int
    {
        $jolts = [0 => 0, 1 => 0, 2 => 0, 3 => 1];

        for ($i = 0; $i < count($this->adapters) - 1; ++$i) {
            $diff = $this->adapters[$i + 1] - $this->adapters[$i];
            ++$jolts[$diff];
        }

        return $jolts[1] * $jolts[3];
    }

    public function part2(): int
    {
        return $this->distinct(0, $this->adapters);
    }

    private function distinct($i, $adapters): int
    {
        if ($i === count($adapters) - 1) {
            return 1;
        }

        if (isset($this->counts[$i])) {
            return $this->counts[$i];
        }

        $count = 0;

        for ($j = $i + 1; $j < count($adapters); ++$j) {
            if (($adapters[$j] - $adapters[$i]) < 4) {
                $count += $this->distinct($j, $adapters);
            }
        }

        $this->counts[$i] = $count;

        return $count;
    }
}
