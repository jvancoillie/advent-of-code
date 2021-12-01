<?php

namespace App\Puzzle\Year2015\Day12;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $decoded = json_decode($input->getData());
        $ans = $this->sumNumbers($decoded);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $decoded = json_decode($input->getData());
        $ans = $this->sumNumbers($decoded, 'red');

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function sumNumbers($array, $excluded = null)
    {
        $sum = 0;
        foreach ($array as $key => $item) {
            if (is_object($item)) {
                $array = (array) $item;
                if (null === $excluded) {
                    $sum += $this->sumNumbers($array, $excluded);
                } elseif (!in_array($excluded, $array)) {
                    $sum += $this->sumNumbers($array, $excluded);
                }
            } elseif (is_array($item)) {
                $sum += $this->sumNumbers($item, $excluded);
            } elseif (is_numeric($item)) {
                $sum += $item;
            }
        }

        return $sum;
    }
}
