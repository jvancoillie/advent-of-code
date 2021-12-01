<?php

namespace App\Puzzle\Year2015\Day08;

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

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach (explode("\n", $input->getData()) as $line) {
            $ans += 2;
            preg_match_all('/(\\\.)/', $line, $m);
            foreach ($m[0] as $r) {
                $ans += ('\x' == $r) ? 3 : 1;
            }
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach (explode("\n", $input->getData()) as $line) {
            $ans += strlen(addslashes($line)) + 2 - strlen($line);
        }

        $output->writeln("<info>Part 2 : $ans</info>");
    }
}
