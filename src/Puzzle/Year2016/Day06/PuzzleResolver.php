<?php

namespace App\Puzzle\Year2016\Day06;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $score = [];
        foreach (explode("\n", $input->getData()) as $line) {
            foreach (str_split($line) as $key => $value) {
                if (!isset($score[$key])) {
                    $score[$key] = [];
                }

                if (isset($score[$key][$value])) {
                    ++$score[$key][$value];
                } else {
                    $score[$key][$value] = 1;
                }
            }
        }

        $ans = '';
        foreach ($score as $values) {
            arsort($values);
            $ans .= array_key_first($values);
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $score = [];
        foreach (explode("\n", $input->getData()) as $line) {
            foreach (str_split($line) as $key => $value) {
                if (!isset($score[$key])) {
                    $score[$key] = [];
                }

                if (isset($score[$key][$value])) {
                    ++$score[$key][$value];
                } else {
                    $score[$key][$value] = 1;
                }
            }
        }

        $ans = '';
        foreach ($score as $values) {
            asort($values);
            $ans .= array_key_first($values);
        }

        $output->writeln("<info>Part 2 : $ans</info>");
    }
}
