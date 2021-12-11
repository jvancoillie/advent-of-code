<?php

namespace App\Puzzle\Year2015\Day17;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Generator;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4;
    protected static int|string $testPart2Expected = 2;

    protected static int|string $part1Expected = 4372;
    protected static int|string $part2Expected = 4;

    private $liters = 150;
    private $containers = [];

    /**
     * @return void
     */
    public function main()
    {
        if ('test' === $this->getOptions()['env']) {
            $this->liters = 25;
        }
        $this->createContainers($this->getInput());
    }

    public function part1()
    {
        $ans = 0;
        foreach (Generator::combinations($this->containers) as $comb) {
            if (array_sum($comb) === $this->liters) {
                ++$ans;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = INF;
        foreach (Generator::combinations($this->containers) as $comb) {
            if (array_sum($comb) === $this->liters && count($comb) < $ans) {
                $ans = count($comb);
            }
        }

        return $ans;
    }

    private function createContainers(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->containers[] = (int) $line;
        }
    }
}
