<?php

namespace App\Puzzle\Year2025\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3;
    protected static int|string $testPart2Expected = 14;

    protected static int|string $part1Expected = 679;
    protected static int|string $part2Expected = 358155203664116;

    public function part1(): int
    {
        [$ranges, $ingredients] = $this->parseInput();

        return $this->getFresh($ranges, $ingredients);
    }

    public function part2(): int
    {
        [$ranges] = $this->parseInput();

        return $this->getAllFresh($ranges);
    }

    private function parseInput(): array
    {
        [$ranges, $ingredients] = array_map(fn (string $r) => explode("\n", $r), explode("\n\n", $this->getInput()->getData()));

        $ingredients = array_map('intval', $ingredients);
        $ranges = array_map(fn (string $e) => array_map('intval', explode('-', $e)), $ranges);

        return [$ranges, $ingredients];
    }

    private function getFresh(mixed $ranges, mixed $ingredients): int
    {
        $count = 0;
        foreach ($ingredients as $ingredient) {
            foreach ($ranges as $range) {
                if ($ingredient >= $range[0] && $ingredient <= $range[1]) {
                    ++$count;
                    break;
                }
            }
        }

        return $count;
    }

    private function getAllFresh(array $ranges): int
    {
        // sort ranges by start
        usort($ranges, static fn ($a, $b) => $a <=> $b);

        $merged = [];
        foreach ($ranges as $r) {
            // if current range is not merged or is not overlapping with last range, merge it and become last range
            if (!$merged || $r[0] > $merged[array_key_last($merged)][1]) {
                $merged[] = $r;
                continue;
            }

            // merge current range with last range.
            // last start becomes new last start
            // last end becomes max of current end and last end
            $last = $merged[array_key_last($merged)];
            $merged[array_key_last($merged)] = [$last[0], max($last[1], $r[1])];
        }

        $total = 0;
        foreach ($merged as [$start, $end]) {
            $total += $end - $start + 1;
        }

        return $total;
    }
}
