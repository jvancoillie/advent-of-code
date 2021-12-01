<?php

namespace App\Puzzle\Year2015\Day02;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    /**
     * @param OutputInterface $output
     *
     * 2*l*w + 2*w*h + 2*h*l.
     * l = 0
     * w = 1
     * h = 2
     */
    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $squareFeet = 0;
        foreach (explode("\n", $input->getData()) as $line) {
            $entry = explode('x', $line);
            $surfaces['lw'] = $entry[0] * $entry[1];
            $surfaces['wh'] = $entry[1] * $entry[2];
            $surfaces['hl'] = $entry[0] * $entry[2];

            $squareFeet += 2 * $surfaces['lw'];
            $squareFeet += 2 * $surfaces['wh'];
            $squareFeet += 2 * $surfaces['hl'];
            $squareFeet += min($surfaces);
        }

        $output->writeln("<info>Part 1 : $squareFeet</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $squareFeet = 0;
        foreach (explode("\n", $input->getData()) as $line) {
            $entry = explode('x', $line);
            sort($entry);
            $min1 = $entry[0];
            $min2 = $entry[1];
            $squareFeet += $min1 + $min1 + $min2 + $min2;
            $squareFeet += $entry[0] * $entry[1] * $entry[2];
        }

        $output->writeln("<info>Part 1 : $squareFeet</info>");
    }
}
