<?php

namespace App\Puzzle\Year2020\Day17;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *  TODO improve this it takes a while ! ! ! ~ 280.0266.
 *
 * @see https://adventofcode.com/2020/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 112;
    protected static int|string $testPart2Expected = 848;

    protected static int|string $part1Expected = 215;
    protected static int|string $part2Expected = 1728;

    private $grid;

    protected function initialize(): void
    {
        $input = array_map('str_split', $this->getInput()->getArrayData());
        // reset x - y to center 0
        $min = (int) -floor(count($input[0]) / 2);
        foreach ($input as $x => $line) {
            foreach ($line as $y => $state) {
                $nx = $min + $x;
                $ny = $min + $y;
                $this->grid[$nx][$ny] = $state;
            }
        }
    }

    public function part1(): int
    {
        $initialCube = [$this->grid];

        $width = count($initialCube[0]);
        $cube = $initialCube;
        for ($i = 2; $i <= 12; $i += 2) {
            $width += $i;
            $round = [];
            $start = (int) -floor($width / 2);
            $end = (int) floor($width / 2);
            for ($z = $start; $z <= $end; ++$z) {
                for ($x = $start; $x <= $end; ++$x) {
                    for ($y = $start; $y <= $end; ++$y) {
                        $round[$z][$x][$y] = $this->toggle3D($z, $x, $y, $cube);
                    }
                }
            }

            $cube = $round;
        }

        return $this->countActive3D($cube);
    }

    public function part2(): int
    {
        $initialCube = [$this->grid];
        $width = count($initialCube[0]);
        $cube = [$initialCube];
        for ($i = 2; $i <= 12; $i += 2) {
            $width += $i;
            $round = [];
            $start = (int) -floor($width / 2);
            $end = (int) floor($width / 2);
            for ($z = $start; $z <= $end; ++$z) {
                for ($x = $start; $x <= $end; ++$x) {
                    for ($y = $start; $y <= $end; ++$y) {
                        for ($w = $start; $w <= $end; ++$w) {
                            $round[$w][$z][$x][$y] = $this->toggle4D($w, $z, $x, $y, $cube);
                        }
                    }
                }
            }

            $cube = $round;
        }

        return $this->countActive4D($cube);
    }

    private function toggle3D($z, $x, $y, $cube): string
    {
        $directions = [
            [-1, -1, -1],
            [-1, -1, 0],
            [-1, -1, 1],
            [-1, 0, -1],
            [-1, 0, 0],
            [-1, 0, 1],
            [-1, 1, -1],
            [-1, 1, 0],
            [-1, 1, 1],

            [0, -1, -1],
            [0, -1, 0],
            [0, -1, 1],
            [0, 0, -1],
            [0, 0, 1],
            [0, 1, -1],
            [0, 1, 0],
            [0, 1, 1],

            [1, -1, -1],
            [1, -1, 0],
            [1, -1, 1],
            [1, 0, -1],
            [1, 0, 0],
            [1, 0, 1],
            [1, 1, -1],
            [1, 1, 0],
            [1, 1, 1],
        ];
        $type = $cube[$z][$x][$y] ?? '.';
        $rules = ['#' => [2, 3], '.' => [3]];
        $activeCount = 0;

        /**
         * @var int $dx
         * @var int $dy
         */
        foreach ($directions as [$dz, $dx, $dy]) {
            $nz = $z + $dz;
            $nx = $x + $dx;
            $ny = $y + $dy;

            $state = $cube[$nz][$nx][$ny] ?? '.';
            if ('#' === $state) {
                ++$activeCount;
            }
        }

        return in_array($activeCount, $rules[$type]) ? '#' : '.';
    }

    private function countActive3D($cube): int
    {
        $active = 0;
        $start = -floor(count($cube[0]) / 2);
        $end = floor(count($cube[0]) / 2);
        for ($z = $start; $z <= $end; ++$z) {
            for ($x = $start; $x <= $end; ++$x) {
                for ($y = $start; $y <= $end; ++$y) {
                    if ('#' === $cube[$z][$x][$y]) {
                        ++$active;
                    }
                }
            }
        }

        return $active;
    }

    private function toggle4D($w, $z, $x, $y, $cube): string
    {
        $type = $cube[$w][$z][$x][$y] ?? '.';
        $rules = ['#' => [2, 3], '.' => [3]];
        $activeCount = 0;

        for ($nw = $w - 1; $nw <= $w + 1; ++$nw) {
            for ($nz = $z - 1; $nz <= $z + 1; ++$nz) {
                for ($nx = $x - 1; $nx <= $x + 1; ++$nx) {
                    for ($ny = $y - 1; $ny <= $y + 1; ++$ny) {
                        if ($nw === $w && $nz === $z && $nx === $x && $ny === $y) {
                            continue;
                        }
                        $state = $cube[$nw][$nz][$nx][$ny] ?? '.';
                        if ('#' === $state) {
                            ++$activeCount;
                        }

                        if ($activeCount > 3) {
                            return '.';
                        }
                    }
                }
            }
        }

        return in_array($activeCount, $rules[$type]) ? '#' : '.';
    }

    private function countActive4D($cube): int
    {
        $active = 0;
        $start = -floor(count($cube[0]) / 2);
        $end = floor(count($cube[0]) / 2);

        for ($z = $start; $z <= $end; ++$z) {
            for ($x = $start; $x <= $end; ++$x) {
                for ($y = $start; $y <= $end; ++$y) {
                    for ($w = $start; $w <= $end; ++$w) {
                        if ('#' === $cube[$w][$z][$x][$y]) {
                            ++$active;
                        }
                    }
                }
            }
        }

        return $active;
    }
}
