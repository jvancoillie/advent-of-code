<?php

namespace App\Puzzle\Year2015\Day09;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\PathFinding\TSP;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 605;
    protected static int|string $testPart2Expected = 982;

    protected static int|string $part1Expected = 117;
    protected static int|string $part2Expected = 909;

    private ?TSP $tsp = null;

    protected function initialize(): void
    {
        $tsp = new TSP();
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            [$from, $to, $dist] = $this->parseLine($line);
            $tsp->add($from, $to, $dist);
        }
        $this->tsp = $tsp;
    }

    public function part1()
    {
        return $this->tsp->getShortestDistance();
    }

    public function part2()
    {
        $maxDistance = 0;

        foreach ($this->tsp->getRoutes() as $route) {
            if ($route['distance'] > $maxDistance) {
                $maxDistance = $route['distance'];
            }
        }

        return $maxDistance;
    }

    public function parseLine(string $line): array
    {
        $pattern = '/(?<from>.*)\sto\s(?<to>.*)\s=\s(?<dist>\d+)/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        return [$matches['from'], $matches['to'], (int) $matches['dist']];
    }
}
