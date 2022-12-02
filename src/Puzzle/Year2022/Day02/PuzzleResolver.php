<?php

namespace App\Puzzle\Year2022\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 15;
    protected static int|string $testPart2Expected = 12;

    protected static int|string $part1Expected = 11475;
    protected static int|string $part2Expected = 16862;

    // rock A, paper B, scissor C
    public const SHAPE_SCORE = ['A' => 1, 'B' => 2, 'C' => 3];
    public const WIN = ['A' => 'B', 'B' => 'C', 'C' => 'A'];
    public const SHAPE_TRANS = ['X' => 'A', 'Y' => 'B', 'Z' => 'C'];

    public function part1(): int
    {
        $data = explode("\n", $this->getInput()->getData());
        $score = 0;

        foreach ($data as $turn) {
            [$p1, $p2] = explode(' ', $turn);
            $p2 = self::SHAPE_TRANS[$p2];

            if ($p1 === $p2) {
                $score += 3 + self::SHAPE_SCORE[$p2];
            } elseif (self::WIN[$p2] == $p1) {
                $score += self::SHAPE_SCORE[$p2];
            } else {
                $score += 6 + self::SHAPE_SCORE[$p2];
            }
        }

        return $score;
    }

    public function part2(): int
    {
        $data = explode("\n", $this->getInput()->getData());
        $score = 0;

        foreach ($data as $turn) {
            [$p1, $state] = explode(' ', $turn);

            switch ($state) {
                case 'X': // need to lose
                    $score += self::SHAPE_SCORE[array_search($p1, self::WIN)];
                    break;
                case 'Y': // need to draw
                    $score += 3 + self::SHAPE_SCORE[$p1];
                    break;
                case 'Z': // need to win
                    $score += 6 + self::SHAPE_SCORE[self::WIN[$p1]];
                    break;
            }
        }

        return $score;
    }
}
