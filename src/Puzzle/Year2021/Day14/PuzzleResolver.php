<?php

namespace App\Puzzle\Year2021\Day14;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1588;
    protected static int|string $testPart2Expected = 2188189693529;

    protected static int|string $part1Expected = 2345;
    protected static int|string $part2Expected = 2432786807053;

    private string $sentence;
    private array $mappings = [];

    protected function initialize(): void
    {
        $data = explode("\n\n", $this->getInput()->getData());

        $this->sentence = $data[0];

        foreach (explode("\n", $data[1]) as $mapping) {
            [$a, $b] = explode(' -> ', $mapping);
            $this->mappings[$a] = $b;
        }
    }

    public function part1(): int
    {
        return $this->process($this->sentence, $this->mappings, 10);
    }

    /**
     * Not found by my self.
     *
     * @see https://github.com/jimeefr/AoC2021/blob/master/14_polymer_part2.py
     */
    public function part2(): int
    {
        return $this->process($this->sentence, $this->mappings, 40);
    }

    protected function process(string $sentence, array $mappings, $steps = 10): int
    {
        $i = 0;
        $len = strlen($sentence);
        $pairs = [];
        $letterCount = [];
        while ($i < $len - 1) {
            if (isset($letterCount[$sentence[$i]])) {
                ++$letterCount[$sentence[$i]];
            } else {
                $letterCount[$sentence[$i]] = 1;
            }

            if (isset($pairs[$sentence[$i].$sentence[$i + 1]])) {
                ++$pairs[$sentence[$i].$sentence[$i + 1]];
            } else {
                $pairs[$sentence[$i].$sentence[$i + 1]] = 1;
            }
            ++$i;
        }

        for ($step = 0; $step < $steps; ++$step) {
            $new = [];
            foreach ($pairs as $pair => $c) {
                if (isset($letterCount[$mappings[$pair]])) {
                    $letterCount[$mappings[$pair]] += $c;
                } else {
                    $letterCount[$mappings[$pair]] = $c;
                }

                if (isset($new[$pair[0].$mappings[$pair]])) {
                    $new[$pair[0].$mappings[$pair]] += $c;
                } else {
                    $new[$pair[0].$mappings[$pair]] = $c;
                }
                if (isset($new[$mappings[$pair].$pair[1]])) {
                    $new[$mappings[$pair].$pair[1]] += $c;
                } else {
                    $new[$mappings[$pair].$pair[1]] = $c;
                }
            }
            $pairs = $new;
        }

        return max($letterCount) - min($letterCount) + 1;
    }
}
