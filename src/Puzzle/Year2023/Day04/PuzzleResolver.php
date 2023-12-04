<?php

namespace App\Puzzle\Year2023\Day04;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 13;
    protected static int|string $testPart2Expected = 30;

    protected static int|string $part1Expected = 25010;
    protected static int|string $part2Expected = 9924412;

    protected array $cards = [];

    protected function initialize(): void
    {
        $this->cards = $this->parse($this->getInput()->getArrayData());
    }

    public function part1(): int
    {
        $ans = 0;

        foreach ($this->cards as $card) {
            $points = 0;
            $winningNumbers = $this->getWinningNumbers($card);
            for ($i = 0; $i < count($winningNumbers); ++$i) {
                if (0 === $points) {
                    $points = 1;
                    continue;
                }

                $points *= 2;
            }

            $ans += $points;
        }

        return $ans;
    }

    public function part2(): int
    {
        $scratchcards = array_fill(1, count($this->cards) + 1, 0);

        foreach ($this->cards as $card) {
            ++$scratchcards[$card[0]];
            $winningNumbers = $this->getWinningNumbers($card);
            for ($i = 1; $i <= count($winningNumbers); ++$i) {
                $scratchcards[$card[0] + $i] += $scratchcards[$card[0]];
            }
        }

        return (int) array_sum($scratchcards);
    }

    private function parse(array $data): array
    {
        $cards = [];

        foreach ($data as $line) {
            preg_match('/Card\s+(?P<card>\d+):(?P<listA>.*)\\|(?P<listB>.*)/', $line, $matches);
            $cards[(int) $matches['card']] = [(int) $matches['card'], preg_split('/\s+/', trim($matches['listA'])), preg_split('/\s+/', trim($matches['listB']))];
        }

        return $cards;
    }

    private function getWinningNumbers($card): array
    {
        return array_intersect($card[2], $card[1]);
    }
}
