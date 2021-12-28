<?php

namespace App\Puzzle\Year2020\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 6;
    protected static int|string $testPart2Expected = 3;

    protected static int|string $part1Expected = 6763;
    protected static int|string $part2Expected = 3512;

    private array $inputs = [];

    public function initialize(): void
    {
        $data = explode("\n\n", $this->getInput()->getData());

        foreach ($data as $entry) {
            $this->inputs[] = array_map('str_split', explode("\n", $entry));
        }
    }

    public function part1(): int
    {
        return $this->declaration(true);
    }

    public function part2(): int
    {
        return $this->declaration(false);
    }

    protected function declaration(bool $unique): int
    {
        $answered = 0;

        foreach ($this->inputs as $group) {
            $answeredList = [];
            $uniqueAnsweredList = [];

            foreach ($group as $k => $person) {
                if (0 === $k) {
                    $answeredList = $person;
                    $uniqueAnsweredList = $person;
                    continue;
                }
                $uniqueAnsweredList = array_merge($uniqueAnsweredList, $person);
                $answeredList = array_intersect($answeredList, $person);
            }
            $answered += ($unique) ? count(array_unique($uniqueAnsweredList)) : count($answeredList);
        }

        return $answered;
    }
}
