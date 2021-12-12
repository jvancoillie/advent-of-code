<?php

namespace App\Puzzle\Year2015\Day13;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\PathFinding\TSP;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/13
 *
 * execute with increase memory limit :
 *  php -d  memory_limit=768M bin/console puzzle:resolve --year=2015 --day=13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 330;
    protected static int|string $testPart2Expected = 286;

    protected static int|string $part1Expected = 733;
    protected static int|string $part2Expected = 725;

    private ?TSP $tsp = null;

    protected function initialize(): void
    {
        $this->createTSP($this->getInput());
    }

    public function part1()
    {
        $ans = $this->tsp->getLongestDistance();
        $path = $this->tsp->getLongestPath();
        $graph = $this->tsp->getGraph();

        $first = array_shift($path);
        $last = array_pop($path);

        return $ans + $graph[$first][$last];
    }

    public function part2()
    {
        $graph = $this->tsp->getGraph();

        foreach ($graph as $person => $next) {
            $this->tsp->add($person, 'me', 0);
        }

        $ans = $this->tsp->getLongestDistance();
        $path = $this->tsp->getLongestPath();
        $graph = $this->tsp->getGraph();

        $first = array_shift($path);
        $last = array_pop($path);

        return $ans + $graph[$first][$last];
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: string, 1: string, 2: int, 3: string}
     */
    public function parseLine(string $line): array
    {
        $pattern = '/^(?<from>.*)\swould\s(?<type>.*)\s(?<bonus>\d+)\shappiness units by sitting next to\s(?<next>.*).$/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        return [$matches['from'], $matches['type'], (int) $matches['bonus'], $matches['next']];
    }

    public function createTSP(PuzzleInput $input): void
    {
        $graph = [];

        foreach (explode("\n", $input->getData()) as $line) {
            [$from, $type, $bonus, $to] = $this->parseLine($line);
            $bonus = ('gain' === $type) ? $bonus : -$bonus;

            if (isset($graph[$from][$to])) {
                $graph[$from][$to] += $bonus;
                $graph[$to][$from] += $bonus;
            } else {
                $graph[$from][$to] = $bonus;
                $graph[$to][$from] = $bonus;
                $graph[$to][$to] = 0;
                $graph[$from][$from] = 0;
            }
        }

        $this->tsp = new TSP($graph);
    }
}
