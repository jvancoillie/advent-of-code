<?php

namespace App\Puzzle\Year2015\Day05;

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
        $patternVowels = '/(?:[aeiou][^aeiou]*){3}/';
        $patternTwice = '/(\w)\1/';
        $patternTReserved = '/(ab|cd|pq|xy)/';
        $nice = 0;

        foreach (explode("\n", $input->getData()) as $line){
            if(preg_match($patternVowels, $line) && preg_match($patternTwice, $line) && !preg_match($patternTReserved, $line) ){
                $nice++;
            }
        }

        $output->writeln("<info>Part 1 : $nice</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $patternPair = '/(..).*?\1/';
        $patternRepeat = '/(.).\1/';
        $nice = 0;

        foreach (explode("\n", $input->getData()) as $line){
            if(preg_match($patternPair, $line) && preg_match($patternRepeat, $line)){
                $nice++;
            }
        }

        $output->writeln("<info>Part 2 : $nice</info>");
    }
}