<?php

namespace App\Puzzle\Year2016\Day08;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Grid;
use Symfony\Component\Console\Helper\Table;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 6;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 106;
    protected static int|string $part2Expected = 0;

    private $instructions = [];
    private $grid = [];

    public function main()
    {
        $this->createInstructions($this->getInput());

        if ('test' === $this->getOptions()['env']) {
            $this->grid = Grid::create(7, 3, '.');
        } else {
            $this->grid = Grid::create(50, 6, ' ');
        }
    }

    public function part1()
    {
        $this->applyInstructions();
        $ans = Grid::count($this->grid, '#');

        return $ans;
    }

    public function part2()
    {
        $this->getOutput()->writeln('<info>Part 2 : Display screen below </info>');

        $table = new Table($this->getOutput());
        $table->setStyle('compact');
        $table->setRows($this->grid);
        $table->render();

        return 0;
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
