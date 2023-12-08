<?php

namespace App\Puzzle\Year2023\Day08;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 6;
    protected static int|string $testPart2Expected = 6;

    protected static int|string $part1Expected = 11567;
    protected static int|string $part2Expected = 9858474970153;

    protected array $graph = [];
    protected array $instructions = [];

    public function initialize(): void
    {
        $data = $this->getInput()->getArrayData();

        $this->instructions = str_split(array_shift($data));

        array_shift($data);

        foreach ($data as $line) {
            preg_match('/(?P<from>.*)\s=\s\((?P<left>.*),\s(?P<right>.*)\)/', $line, $matches);
            $this->graph[$matches['from']] = [$matches['left'], $matches['right']];
        }
    }

    public function part1()
    {
        $steps = 0;
        $node = 'AAA';

        while ('ZZZ' !== $node) {
            $node = ('R' === $this->instructions[$steps % count(
                $this->instructions
            )]) ? $this->graph[$node][1] : $this->graph[$node][0];
            ++$steps;
        }

        return $steps;
    }

    public function part2()
    {
        $nodes = [];

        foreach ($this->graph as $node => $values) {
            if (str_ends_with($node, 'A')) {
                $steps = 0;
                while (!str_ends_with($node, 'Z')) {
                    $node = ('R' === $this->instructions[$steps % count(
                        $this->instructions
                    )]) ? $this->graph[$node][1] : $this->graph[$node][0];
                    ++$steps;
                }
                $nodes[] = $steps;
            }
        }

        return array_reduce($nodes, function ($a, $b) {
            return $a * $b / $this->gcd($a, $b);
        }, 1);
    }

    public function gcd($a, $b)
    {
        while (0 != $b) {
            [$a, $b] = [$b, $a % $b];
        }

        return $a;
    }
}
