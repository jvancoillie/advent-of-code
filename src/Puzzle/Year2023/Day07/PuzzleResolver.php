<?php

namespace App\Puzzle\Year2023\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 6440;
    protected static int|string $testPart2Expected = 5905;

    protected static int|string $part1Expected = 251927063;
    protected static int|string $part2Expected = 255632664;
    public const STRENGTH = ['A' => 14, 'K' => 13, 'Q' => 12, 'J' => 11, 'T' => 10, '9' => 9, '8' => 8, '7' => 7, '6' => 6, '5' => 5, '4' => 4, '3' => 3, '2' => 2, '1' => 1];

    public function part1(): int
    {
        $ans = 0;

        $data = array_map(fn ($e) => explode(' ', $e), $this->getInput()->getArrayData());

        uasort($data, function ($a, $b) {
            $evalA = $this->evaluate($a[0]);
            $evalB = $this->evaluate($b[0]);

            if ($evalB === $evalA) {
                return $this->strongest($a[0], $b[0]);
            }

            return $evalA <=> $evalB;
        });

        $rank = 1;

        foreach ($data as $d) {
            $ans += $rank * $d[1];
            ++$rank;
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = array_map(fn ($e) => explode(' ', $e), $this->getInput()->getArrayData());

        uasort($data, function ($a, $b) {
            $evalA = $this->evaluate($a[0], true);
            $evalB = $this->evaluate($b[0], true);

            if ($evalB === $evalA) {
                return $this->strongest($a[0], $b[0], true);
            }

            return $evalA <=> $evalB;
        });

        $rank = 1;

        foreach ($data as $d) {
            $ans += $rank * $d[1];
            ++$rank;
        }

        return $ans;
    }

    public function evaluate(string $cards, $part2 = false): int
    {
        $count = count_chars($cards, 1);
        $n = 0;

        if ($part2 && isset($count['74'])) {
            $n = $count['74'];
            unset($count['74']);
        }

        rsort($count);

        if ($part2 && $n > 0) {
            if (!isset($count[0])) {
                $count = [0];
            }

            $count[0] += $n;
        }

        // Five of a kind
        if (1 === count($count)) {
            return 6;
        }

        if (2 === count($count)) {
            // Four of a kind
            if (4 === $count[0]) {
                return 5;
            }
            // Full house
            if (3 === $count[0]) {
                return 4;
            }
        }

        if (3 === count($count)) {
            // Three of a kind
            if (3 === $count[0]) {
                return 3;
            }

            // Two pair
            if (2 === $count[0]) {
                return 2;
            }
        }

        // One pair
        if (4 === count($count)) {
            return 1;
        }

        // High card
        return 0;
    }

    public function strongest($cardsA, $cardsB, $part2 = false): int
    {
        if ($part2) {
            $cardsA = str_replace('J', '1', $cardsA);
            $cardsB = str_replace('J', '1', $cardsB);
        }

        for ($i = 0; $i < strlen($cardsA); ++$i) {
            if (self::STRENGTH[$cardsA[$i]] > self::STRENGTH[$cardsB[$i]]) {
                return 1;
            }

            if (self::STRENGTH[$cardsA[$i]] < self::STRENGTH[$cardsB[$i]]) {
                return -1;
            }
        }

        return 0;
    }
}
