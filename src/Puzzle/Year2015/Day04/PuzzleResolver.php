<?php

namespace App\Puzzle\Year2015\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output)
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $i=0;
        $md5 = '';
        $string = $input->getData();
        while(strpos($md5, '00000') !== 0){
            $i++;
            $md5 = md5($string.$i);
        }
        $output->writeln("<info>Part 1 : $i</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $i=0;
        $md5 = '';
        $string = $input->getData();
        while(strpos($md5, '000000') !== 0){
            $i++;
            $md5 = md5($string.$i);
        }
        $output->writeln("<info>Part 2 : $i</info>");
    }
}