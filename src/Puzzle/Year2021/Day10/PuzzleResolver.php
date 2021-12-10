<?php

namespace App\Puzzle\Year2021\Day10;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private int $corruptScore = 0;
    private array $missingScores = [];
    private const CORRUPT_SCORE = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
    private const MISSING_SCORE = [')' => 1, ']' => 2, '}' => 3, '>' => 4];
    private const OPPOSITE = ['(' => ')', '[' => ']', '{' => '}', '<' => '>'];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->parse(explode("\n", $input->getData()));

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $ans = $this->corruptScore;
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        // take the median of all missing scores

        sort($this->missingScores);
        $ans = $this->missingScores[count($this->missingScores) / 2];

        $output->writeln("<info>Part 2 : $ans</info>");
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
