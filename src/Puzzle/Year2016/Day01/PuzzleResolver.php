<?php

namespace App\Puzzle\Year2016\Day01;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @see https://adventofcode.com/2016/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $instructions = [];
    private $currentDirection = 'N';
    private $directions = ['N', 'E', 'S', 'W'];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createInstructions($input);

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        [$x, $y] = $this->walk();
        $ans = $this->manhattan(0, 0, $x, $y);
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        [$x, $y] = $this->walk(true);
        $ans = $this->manhattan(0, 0, $x, $y);
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function createInstructions(PuzzleInput $input)
    {
        foreach (explode(", ", $input->getData()) as $line) {
            $this->instructions[] = ['turn' => substr($line, 0, 1), 'dist' => (int)substr($line, 1)];
        }
    }

    private function walk($firstVisited = false)
    {
        $x = $y = 0;
        $this->currentDirection = 'N';
        $visited = [];
        foreach ($this->instructions as $instruction) {
            $this->turn($instruction['turn']);
            $targets = [];
            switch ($this->currentDirection) {
                case 'N':
                    for ($i = 0; $i < $instruction['dist']; $i++) {
                        $y--;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'S':
                    for ($i = 0; $i < $instruction['dist']; $i++) {
                        $y++;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'E':
                    for ($i = 0; $i < $instruction['dist']; $i++) {
                        $x++;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'W':
                    for ($i = 0; $i < $instruction['dist']; $i++) {
                        $x--;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
            }
            if ($firstVisited) {
                foreach ($targets as $target) {
                    $check = $target['x'].'-'.$target['y'];
                    if (in_array($check, $visited)) {
                        return [$target['x'], $target['y']];
                    }
                    $visited[] = $check;
                }
            }
        }

        return [$x, $y];
    }

    private function manhattan($xa, $ya, $xb, $yb)
    {
        return abs($ya - $yb) + abs($xa - $xb);
    }

    private function turn($direction)
    {
        $key = array_search($this->currentDirection, $this->directions);

        if ($direction === 'R') {
            $key++;
        } else {
            $key--;
        }

        if ($key < 0) {
            $key = count($this->directions) - 1;
        }
        $key %= (count($this->directions));

        $this->currentDirection = $this->directions[$key];
    }
}