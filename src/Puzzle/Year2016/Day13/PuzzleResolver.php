<?php

namespace App\Puzzle\Year2016\Day13;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\PathFinding\Dijkstra;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private array $reach;
    private int $favoriteNumber;

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->reach = ('test' === $options['env']) ? [7, 4] : [31, 39];

        $this->favoriteNumber = $input->getData();

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $maze = new Maze($this->favoriteNumber);

        $start = $maze->createPoint(1, 1);
        $goal = $maze->createPoint($this->reach[0], $this->reach[1]);

        $dijkstra = new Dijkstra($maze);

        $path = $dijkstra->findPath($start, $goal);

        $ans = count($path) - 1;
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        $ans = 0;

        $output->writeln("<info>Part 2 : $ans</info>");
    }
}
