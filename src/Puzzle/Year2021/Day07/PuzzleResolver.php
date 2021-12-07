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
        $fuels = [];

        for ($i = 0; $i < $max; ++$i) {
            $fuels[] = array_reduce($data, function ($carry, $pos) use ($i, $expensive) {
                return $carry + $this->spend(abs($pos - $i), $expensive);
            });
        }

        return min($fuels);
    }

    public function spend($n, $expensive): int
    {
        return ($expensive) ? $n * (1 + $n) / 2 : $n;
    }
}
