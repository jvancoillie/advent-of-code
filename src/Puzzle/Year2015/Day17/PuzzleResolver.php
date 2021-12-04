<?php

namespace App\Puzzle\Year2015\Day17;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Generator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $liters = 150;
    private $containers = [];

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        if ('test' === $options['env']) {
            $this->liters = 25;
        }
        $this->createContainers($input);
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = 0;
        foreach (Generator::combinations($this->containers) as $comb) {
            if (array_sum($comb) === $this->liters) {
                ++$ans;
            }
        }
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = INF;
        foreach (Generator::combinations($this->containers) as $comb) {
            if (array_sum($comb) === $this->liters && count($comb) < $ans) {
                $ans = count($comb);
            }
        }
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function createContainers(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->containers[] = (int) $line;
        }
    }
}
