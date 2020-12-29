<?php

namespace App\Puzzle\Year2015\Day18;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Grid;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @see https://adventofcode.com/2015/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $grid = [];
    private $steps = 100;
    private $directions = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [1, -1],
        [1, 0],
        [1, 1],
        [0, -1],
        [0, 1],
    ];
    private $height;
    private $width;
    private $cornersLocked = false;

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        if ($options['env'] === 'test') {
            $this->steps = 5;
        }

        $this->createGrid($input);
        $this->part1($input, $output);

        $this->grid = [];
        $this->createGrid($input);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        for ($i = 0; $i < $this->steps; $i++) {
            $this->toggleLights();
        }

        $ans = $this->countLightOn();

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        //turn on corners;
        $this->turnOnCorners();

        for ($i = 0; $i < $this->steps; $i++) {
            $this->toggleLights();
        }

        $ans = $this->countLightOn();

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function createGrid(PuzzleInput $input)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->grid[] = str_split($line);
        }

        $this->height = count($this->grid) - 1;
        $this->width = count($this->grid[0]) - 1;
    }

    public function togglelights()
    {
        //Grid::dump($this->grid);
        $grid = [];

        for ($y = 0; $y <= $this->height; $y++) {
            for ($x = 0; $x <= $this->width; $x++) {
                $grid[$y][$x] = $this->grid[$y][$x];

                // use for part 2 locked corners
                if ($this->cornersLocked &&
                    (
                        ($x === 0 && $y === 0) || ($x === 0 && $y === $this->width) ||
                        ($x === $this->height && $y === 0) || ($x === $this->height && $y === $this->width)
                    )
                ) {
                    continue;
                }

                $on = 0;
                $off = 0;

                /**
                 * @var int $dx
                 * @var int $dy
                 */
                foreach ($this->directions as [$dx, $dy]) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;
                    if (isset($this->grid[$ny][$nx])) {
                        if ($this->grid[$ny][$nx] === "#") {
                            $on++;
                        } else {
                            $off++;
                        }
                    }
                }
                if ($this->grid[$y][$x] === '#') {
                    if ($on < 2 || $on > 3) {
                        $grid[$y][$x] = '.';
                    }
                }
                if ($this->grid[$y][$x] === '.') {
                    if ($on === 3) {
                        $grid[$y][$x] = '#';
                    }
                }
            }
        }

        $this->grid = $grid;
    }

    public function turnOnCorners()
    {
        $this->grid[0][0] = '#';
        $this->grid[0][$this->width] = '#';
        $this->grid[$this->height][0] = '#';
        $this->grid[$this->height][$this->width] = '#';

        $this->cornersLocked = true;
    }

    public function countLightOn()
    {
        $on = 0;
        for ($y = 0; $y <= $this->height; $y++) {
            for ($x = 0; $x <= $this->width; $x++) {
                if ($this->grid[$y][$x] === '#') {
                    $on++;
                }
            }
        }

        return $on;
    }
}