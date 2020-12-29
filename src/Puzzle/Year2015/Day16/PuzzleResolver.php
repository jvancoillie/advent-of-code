<?php

namespace App\Puzzle\Year2015\Day16;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @see https://adventofcode.com/2015/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $tickerTape = [
        "children" => 3,
        "cats" => 7,
        "samoyeds" => 2,
        "pomeranians" => 3,
        "akitas" => 0,
        "vizslas" => 0,
        "goldfish" => 5,
        "trees" => 3,
        "cars" => 2,
        "perfumes" => 1,
    ];

    private $aunts = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createAunts($input);
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach ($this->aunts as $key => $aunt){
            if($this->match($aunt)){
                $ans = $key;
            }
        }
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach ($this->aunts as $key => $aunt){
            if($this->match2($aunt)){
                $ans = $key;
            }
        }
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function createAunts(PuzzleInput $input)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    private function parseLine($line)
    {
        $pattern ='/^Sue\s(?<id>\d+):\s(?<properties>.*)$/';

        preg_match($pattern, $line, $matches);

        if(!$matches) {
            throw new \Exception("parsing action error");
        }
        $propertiesData = explode(', ', $matches['properties']);
        foreach ($propertiesData as $propertyData) {
            [$property, $number] = explode(': ', $propertyData);
            $properties[$property] = (int)$number;
        }

        $this->aunts[$matches['id']] = $properties;
    }

    private function match($aunt)
    {
        foreach($aunt as $property => $number){
            if($this->tickerTape[$property] !== $number){
                return false;
            }
        }

        return true;

    }


    private function match2($aunt)
    {
        foreach($aunt as $property => $number){
            if($property === 'cats' || $property === 'trees'){
                if($this->tickerTape[$property] >= $number){
                    return false;
                }
            }elseif($property === 'pomeranians' || $property === 'goldfish'){
                if($this->tickerTape[$property] <= $number){
                    return false;
                }
            }elseif($this->tickerTape[$property] !== $number){
                return false;
            }
        }

        return true;

    }
}