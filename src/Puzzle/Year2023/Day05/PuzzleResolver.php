<?php

namespace App\Puzzle\Year2023\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 35;
    protected static int|string $testPart2Expected = 46;

    protected static int|string $part1Expected = 836040384;
    protected static int|string $part2Expected = 10834440;

    protected array $seeds = [];
    protected array $data = [];
    protected array $ranges = [];

    protected function initialize(): void
    {
        [$this->seeds, $this->ranges] = $this->parse($this->getInput()->getData());
    }

    public function part1()
    {
        $r = [];
        foreach ($this->seeds as $seed) {
            foreach ($this->ranges as $type) {
                foreach ($type as [$to, $from, $length]) {
                    if ($from <= $seed && $seed < ($from + $length)) {
                        $seed = $to + $seed - $from;
                        break;
                    }
                }
            }

            $r[] = $seed;
        }

        return min($r);
    }

    public function part2()
    {
        $seeds = array_map(fn ($e) => [(int) $e[0], $e[0] + $e[1] - 1], array_chunk($this->seeds, 2));

        foreach ($this->ranges as $type) {
            $nextSeeds = [];
            foreach ($type as [$to, $typeFrom, $length]) {
                $newSeeds = [];
                $typeEnd = $typeFrom + $length;
                while ($seeds) {
                    [$seedFrom, $seedEnd] = array_pop($seeds);

                    if ($seedFrom < min($seedEnd, $typeFrom)) {
                        $newSeeds[] = [$seedFrom, min($seedEnd, $typeFrom)];
                    }

                    if ($seedEnd > max($typeEnd, $seedFrom)) {
                        $newSeeds[] = [max($typeEnd, $seedFrom), $seedEnd];
                    }

                    if (max($seedFrom, $typeFrom) < min($seedEnd, $typeEnd)) {
                        $nextSeeds[] = [$to + max($seedFrom, $typeFrom) - $typeFrom, $to + min($seedEnd, $typeEnd) - $typeFrom];
                    }
                }
                $seeds = $newSeeds;
            }
            $seeds = $seeds + $nextSeeds;
        }

        return min(array_map(fn ($e) => $e[0], $seeds));
    }

    private function parse($data): array
    {
        $parsed = ['seeds' => [], 'ranges' => []];

        foreach (explode("\n\n", $data) as $i => $bloc) {
            if (0 === $i) {
                preg_match('/seeds:(?P<seeds>.*)/', $bloc, $matches);
                $parsed['seeds'] = explode(' ', trim($matches['seeds']));
                continue;
            }

            $ranges = [];
            foreach (explode("\n", $bloc) as $j => $map) {
                if (0 === $j) {
                    continue;
                }

                $range = explode(' ', $map);
                $ranges[] = $range;
            }
            $parsed['ranges'][] = $ranges;
        }

        return [$parsed['seeds'], $parsed['ranges']];
    }
}
