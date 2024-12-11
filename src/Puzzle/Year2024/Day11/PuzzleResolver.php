<?php

namespace App\Puzzle\Year2024\Day11;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 55312;
    protected static int|string $testPart2Expected = 65601038650482;

    protected static int|string $part1Expected = 183620;
    protected static int|string $part2Expected = 220377651399268;

    public function part1(): int
    {
        $stones = array_map('intval', explode(' ', $this->getInput()->getData()));

        return $this->blink($stones, 25);
    }

    public function part2(): int
    {
        $stones = array_map('intval', explode(' ', $this->getInput()->getData()));

        return $this->blink($stones, 75);
    }

    private function blink(array $stones, int $blinks): int
    {
        $stoneCounts = array_count_values($stones);

        for ($i = 0; $i < $blinks; ++$i) {
            $newStoneCounts = [];

            foreach ($stoneCounts as $stone => $count) {
                if (0 === $stone) {
                    $newStoneCounts[1] = ($newStoneCounts[1] ?? 0) + $count;
                    continue;
                }

                $stoneAsString = (string) $stone;

                if (0 === strlen($stoneAsString) % 2) {
                    $mid = strlen($stoneAsString) / 2;
                    $leftPart = (int) substr($stoneAsString, 0, $mid);
                    $rightPart = (int) substr($stoneAsString, $mid);
                    $newStoneCounts[$leftPart] = ($newStoneCounts[$leftPart] ?? 0) + $count;
                    $newStoneCounts[$rightPart] = ($newStoneCounts[$rightPart] ?? 0) + $count;
                } else {
                    $newStone = $stone * 2024;
                    $newStoneCounts[$newStone] = ($newStoneCounts[$newStone] ?? 0) + $count;
                }
            }

            $stoneCounts = $newStoneCounts;
        }

        return array_sum($stoneCounts);
    }
}
