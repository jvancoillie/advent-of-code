<?php

namespace App\Puzzle\Year2021\Day05;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @var Segment[]
     */
    private array $segments;

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->parseInput($input);

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output): void
    {
        $ans = $this->countOverlapping(false);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output): void
    {
        $ans = $this->countOverlapping(true);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function parseInput(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $entry) {
            if (preg_match('/(?<x1>\d+),(?<y1>\d+) -> (?<x2>\d+),(?<y2>\d+)/', $entry, $matches)) {
                $this->segments[] = new Segment($matches['x1'], $matches['y1'], $matches['x2'], $matches['y2']);
            }
        }
    }

    private function countOverlapping(bool $withDiagonals): int
    {
        $grid = [];

        foreach ($this->segments as $segment) {
            foreach ($segment->getPoints($withDiagonals) as [$x, $y]) {
                if (isset($grid[$y][$x])) {
                    ++$grid[$y][$x];
                } else {
                    $grid[$y][$x] = 1;
                }
            }
        }

        // count overlapping vents in grid, x,y > 1
        return array_reduce($grid, function ($carry, $item) { return $carry + count(array_filter($item, function ($e) {return $e > 1; })); });
    }
}
