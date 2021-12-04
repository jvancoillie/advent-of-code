<?php

namespace App\Puzzle\Year2016\Day08;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Grid;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $instructions = [];
    private $grid = [];

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createInstructions($input);

        if ('test' === $options['env']) {
            $this->grid = Grid::create(7, 3, '.');
        } else {
            $this->grid = Grid::create(50, 6, ' ');
        }

        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $this->applyInstructions();
        $ans = Grid::count($this->grid, '#');

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $output->writeln('<info>Part 2 : Display screen below </info>');

        $table = new Table($output);
        $table->setStyle('compact');
        $table->setRows($this->grid);
        $table->render();
    }

    private function createInstructions(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $exploded = explode(' ', $line);
            $instruction = [];
            $instruction['action'] = $exploded[0];

            if ('rect' === $exploded[0]) {
                [$x, $y] = explode('x', $exploded[1]);
                $instruction['coord']['x'] = $x;
                $instruction['coord']['y'] = $y;
            } else {
                $instruction['type'] = $exploded[1];
                $info = explode('=', $exploded[2]);
                $instruction['coord'] = $info[1];
                $instruction['by'] = $exploded[4];
            }

            $this->instructions[] = $instruction;
        }
    }

    private function applyInstructions(): void
    {
        foreach ($this->instructions as $instruction) {
            if ('rect' === $instruction['action']) {
                $this->turnOn($instruction['coord']['x'], $instruction['coord']['y']);
            } else {
                if ('column' === $instruction['type']) {
                    $this->rotateColumn($instruction['coord'], $instruction['by']);
                } else {
                    $this->rotateRow($instruction['coord'], $instruction['by']);
                }
            }
        }
    }

    private function turnOn($xSize, $ySize): void
    {
        for ($y = 0; $y < $ySize; ++$y) {
            for ($x = 0; $x < $xSize; ++$x) {
                $this->grid[$y][$x] = '#';
            }
        }
    }

    private function rotateRow($y, $by): void
    {
        $row = $this->grid[$y];
        for ($i = 0; $i < $by; ++$i) {
            array_unshift($row, array_pop($row));
        }
        $this->grid[$y] = $row;
    }

    private function rotateColumn($x, $by): void
    {
        $col = [];
        foreach ($this->grid as $y => $line) {
            $col[$y] = $line[$x];
        }
//        dump($col);
        for ($i = 0; $i < $by; ++$i) {
            array_unshift($col, array_pop($col));
        }
//        dump($col);
        foreach ($this->grid as $y => $line) {
            $this->grid[$y][$x] = $col[$y];
        }
    }
}
