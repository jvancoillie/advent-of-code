<?php

namespace App\Puzzle\Year2015\Day20;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/20
 *
 * Not found by my self
 * @see https://medium.com/@ghaiklor/advent-of-code-2015-explanation-aa9932db6d6f#9795
 *
 * need to run with php -d  memory_limit=2048M bin/console puzzle:resolve --year=2015 --day=20
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
        $input = (int) $input->getData() / 10;
        $houses = [];
        $houseNumber = $input;

        for ($i = 1; $i < $input; ++$i) {
            for ($j = $i; $j < $input; $j += $i) {
                if (!isset($houses[$j])) {
                    $houses[$j] = 0;
                }
                if (($houses[$j] += $i) >= $input && $j < $houseNumber) {
                    $houseNumber = $j;
                }
            }
        }

        $output->writeln("<info>Part 1 : $houseNumber</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $input = (int) $input->getData() / 10;
        $houses = [];
        $houseNumber = $input;

        for ($i = 1; $i < $input; ++$i) {
            $visits = 0;
            for ($j = $i; $j < $input; $j += $i) {
                if (!isset($houses[$j])) {
                    $houses[$j] = 11;
                }
                if (($houses[$j] = $houses[$j] + $i * 11) >= $input * 10 && $j < $houseNumber) {
                    $houseNumber = $j;
                }

                ++$visits;
                if (50 === $visits) {
                    break;
                }
            }
        }

        $output->writeln("<info>Part 2 : $houseNumber</info>");
    }
}
