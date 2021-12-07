<?php

namespace App\Puzzle\Year2021\Day07;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/7
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
        $ans = $this->cheapestFuelToAlign(explode(',', $input->getData()));

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $this->cheapestFuelToAlign(explode(',', $input->getData()), true);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function cheapestFuelToAlign($data, $expensive = false): float|int
    {
        $max = max($data);
        $best = INF;

        for ($i = 0; $i < $max; ++$i) {
            $sum = 0;
            foreach ($data as $p) {
                $diff = abs($p - $i);
                $sum += $expensive ? $this->spend($diff) : $diff;
            }

            if ($sum < $best) {
                $best = $sum;
            }
        }

        return $best;
    }

    public function spend($n): int
    {
        return $n * (1 + $n) / 2;
    }
}
