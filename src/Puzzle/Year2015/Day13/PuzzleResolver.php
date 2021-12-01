<?php

namespace App\Puzzle\Year2015\Day13;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\PathFinding\TSP;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/13
 *
 * execute with increase memory limit :
 *  php -d  memory_limit=2048M bin/console puzzle:resolve --year=2015 --day=13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @var TSP
     */
    private $tsp;

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createTSP($input);
        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $ans = $this->tsp->getLongestDistance();
        $path = $this->tsp->getLongestPath();
        $graph = $this->tsp->getGraph();

        $first = array_shift($path);
        $last = array_pop($path);

        $ans += $graph[$first][$last];

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
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

        $ans += $graph[$first][$last];

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function parseLine($line)
    {
        $pattern = '/^(?<from>.*)\swould\s(?<type>.*)\s(?<bonus>\d+)\shappiness units by sitting next to\s(?<next>.*).$/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        return [$matches['from'], $matches['type'], $matches['bonus'], $matches['next']];
    }

    public function createTSP(PuzzleInput $input)
    {
        $graph = [];

        foreach (explode("\n", $input->getData()) as $line) {
            [$from, $type, $bonus, $to] = $this->parseLine($line);
            $bonus = ('gain' === $type) ? (int) $bonus : (int) -$bonus;

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
