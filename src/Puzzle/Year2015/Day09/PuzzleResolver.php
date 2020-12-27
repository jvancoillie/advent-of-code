<?php

namespace App\Puzzle\Year2015\Day09;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\PathFinding\TSP;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    private $tsp;

    public function main(PuzzleInput $input, OutputInterface $output)
    {
        $tsp = new TSP();
        foreach (explode("\n", $input->getData()) as $line) {
            [$from, $to, $dist] = $this->parseLine($line);
            $tsp->add($from, $to, $dist);
        }
        $this->tsp = $tsp;

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $ans = $this->tsp->getShortestDistance();

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        $maxDistance = 0;

        foreach($this->tsp->getRoutes() as $route){
            if($route['distance'] > $maxDistance){
                $maxDistance = $route['distance'];
            }
        }

        $output->writeln("<info>Part 2 : $maxDistance</info>");
    }

    public function parseLine($line)
    {
        $pattern = '/(?<from>.*)\sto\s(?<to>.*)\s=\s(?<dist>\d+)/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception("parsing action error");
        }

        return [$matches['from'], $matches['to'], (int)$matches['dist']];
    }
}