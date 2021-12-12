<?php

namespace App\Puzzle\Year2015\Day18;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4;
    protected static int|string $testPart2Expected = 17;

    protected static int|string $part1Expected = 768;
    protected static int|string $part2Expected = 781;

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

    protected function initialize(): void
    {
        if ('test' === $this->getOptions()['env']) {
            $this->steps = 5;
        }
    }

    public function part1()
    {
        $this->createGrid($this->getInput());

        for ($i = 0; $i < $this->steps; ++$i) {
            $this->toggleLights();
        }

        return $this->countLightOn();
    }

    public function part2()
    {
        $this->grid = [];
        $this->createGrid($this->getInput());

        //turn on corners;
        $this->turnOnCorners();

        for ($i = 0; $i < $this->steps; ++$i) {
            $this->toggleLights();
        }

        return $this->countLightOn();
    }

    public function createGrid(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->grid[] = str_split($line);
        }

        $this->height = count($this->grid) - 1;
        $this->width = count($this->grid[0]) - 1;
    }

    public function togglelights(): void
    {
        $grid = [];

        for ($y = 0; $y <= $this->height; ++$y) {
            for ($x = 0; $x <= $this->width; ++$x) {
                $grid[$y][$x] = $this->grid[$y][$x];

                // use for part 2 locked corners
                if ($this->cornersLocked &&
                    (
                        (0 === $x && 0 === $y) || (0 === $x && $y === $this->width) ||
                        ($x === $this->height && 0 === $y) || ($x === $this->height && $y === $this->width)
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
                        if ('#' === $this->grid[$ny][$nx]) {
                            ++$on;
                        } else {
                            ++$off;
                        }
                    }
                }
                if ('#' === $this->grid[$y][$x]) {
                    if ($on < 2 || $on > 3) {
                        $grid[$y][$x] = '.';
                    }
                }
                if ('.' === $this->grid[$y][$x]) {
                    if (3 === $on) {
                        $grid[$y][$x] = '#';
                    }
                }
            }
        }

        $this->grid = $grid;
    }

    public function turnOnCorners(): void
    {
        $this->grid[0][0] = '#';
        $this->grid[0][$this->width] = '#';
        $this->grid[$this->height][0] = '#';
        $this->grid[$this->height][$this->width] = '#';

        $this->cornersLocked = true;
    }

    /**
     * @psalm-return 0|positive-int
     */
    public function countLightOn(): int
    {
        $on = 0;
        for ($y = 0; $y <= $this->height; ++$y) {
            for ($x = 0; $x <= $this->width; ++$x) {
                if ('#' === $this->grid[$y][$x]) {
                    ++$on;
                }
            }
        }

        return $on;
    }
}
