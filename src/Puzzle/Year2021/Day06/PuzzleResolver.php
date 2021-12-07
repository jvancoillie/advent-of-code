<?php

namespace App\Puzzle\Year2021\Day06;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/6
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
        $data = explode(',', $input->getData());

        $ans = $this->grow($data, 80);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $data = explode(',', $input->getData());

        $ans = $this->grow($data, 256);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function grow($data, $days): float|int
    {
        $d = array_fill(0, 9, 0);
        foreach (array_count_values($data) as $key => $count) {
            $d[(int) $key] = $count;
        }

        $i = 0;
        while ($i++ < $days) {
            $d[7] += $d[0];
            array_push($d, array_shift($d));
        }

        return array_sum($d);
    }
}
