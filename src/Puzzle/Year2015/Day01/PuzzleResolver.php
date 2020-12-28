<?php

namespace App\Puzzle\Year2015\Day01;

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
        $floor = 0;
        foreach(str_split($input->getData()) as $data){
            if($data === '('){
                $floor++;
            }elseif ($data === ')'){
                $floor--;
            }
        }

        $output->writeln("<info>Part 1 : $floor</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $floor = 0;
        foreach(str_split($input->getData()) as $key => $data){
            if($data === '('){
                $floor++;
            }elseif ($data === ')'){
                $floor--;
            }

            if($floor<0){
                $response = $key +1;
                break;

            }
        }

        $output->writeln("<info>Part 2 : $response</info>");
    }
}