<?php

namespace App\Puzzle\Year2021\Day10;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int $testPart1Expected = 26397;
    protected static int $testPart2Expected = 288957;

    protected static int $part1Expected = 344193;
    protected static int $part2Expected = 3241238967;

    private int $corruptScore = 0;
    private array $missingScores = [];
    private const CORRUPT_SCORE = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
    private const MISSING_SCORE = [')' => 1, ']' => 2, '}' => 3, '>' => 4];
    private const OPPOSITE = ['(' => ')', '[' => ']', '{' => '}', '<' => '>'];

    public function part1(): int
    {
        $this->parse(explode("\n", $this->getInput()->getData()));

        return $this->corruptScore;
    }

    public function part2()
    {
        // take the median of all missing scores
        $this->parse(explode("\n", $this->getInput()->getData()));

        sort($this->missingScores);

        return $this->missingScores[count($this->missingScores) / 2];
    }

    public function parse($data): void
    {
        foreach ($data as $line) {
            $r = [];
            $corrupt = false;
            foreach (str_split($line) as $e) {
                if (in_array($e, ['(', '[', '{', '<'])) {
                    array_unshift($r, self::OPPOSITE[$e]);
                } else {
                    $expected = array_shift($r);
                    if ($e !== $expected) {
                        $corrupt = true;
                        $this->corruptScore += self::CORRUPT_SCORE[$e];
                    }
                }
            }

            if (!$corrupt) {
                $score = 0;

                foreach ($r as $e) {
                    $score *= 5;
                    $score += self::MISSING_SCORE[$e];
                }

                $this->missingScores[] = $score;
            }
        }
    }
}
