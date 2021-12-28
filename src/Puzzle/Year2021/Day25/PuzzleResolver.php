<?php

namespace App\Puzzle\Year2021\Day25;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/25
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 0;
    protected static int|string $part2Expected = 0;

    public function part1()
    {
        $data = explode("\n", $this->getInput()->getData());
        $grid = array_map('str_split', $data);

        $move = true;
        $step = 0;

        dump('Initial state:');
        Grid::dump($grid);

        while ($move) {
            ++$step;
            [$grid, $moveEast] = $this->moveEast($grid);
            [$grid, $moveSouth] = $this->moveSouth($grid);
            dump("After $step step : ".(int) $move);
            Grid::dump($grid);
            $move = $moveEast || $moveSouth;
        }

//            if($step > 5){
//                break;
//            }

        return $step;
    }

    public function part2()
    {
        return 0;
    }

    public function moveEast(array $grid)
    {
        $move = false;
        $done = [];
        for ($y = 0; $y < count($grid); ++$y) {
            $leftState = $grid[$y][0];
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if (!isset($done["$y-$x"])) {
                    if ('>' === $grid[$y][$x]) {
                        if (isset($grid[$y][$x + 1])) {
                            if ('.' === $grid[$y][$x + 1]) {
                                $grid[$y][$x + 1] = '>';
                                $grid[$y][$x] = '.';
                                $move = true;
                                $done["$y-".($x + 1)] = 0;
                            }
                        } elseif (isset($grid[$y][0]) && '.' === $leftState) {
                            $grid[$y][0] = '>';
                            $grid[$y][$x] = '.';
                            $move = true;
                            $done["$y-0"] = 0;
                        }
                    }
                }
            }
        }

        return [$grid, $move];
    }

    public function moveSouth(array $grid)
    {
        $move = false;
        $done = [];
        $topState = $grid[0];
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if (!isset($done["$y-$x"])) {
                    if ('v' === $grid[$y][$x]) {
                        if (isset($grid[$y + 1][$x])) {
                            if ('.' === $grid[$y + 1][$x]) {
                                $grid[$y + 1][$x] = 'v';
                                $grid[$y][$x] = '.';
                                $done[($y + 1)."-$x"] = 0;
                                $move = true;
                            }
                        } elseif (isset($grid[0][$x]) && '.' === $topState[$x]) {
                            $grid[0][$x] = 'v';
                            $grid[$y][$x] = '.';
                            $done["0-$x"] = 0;
                            $move = true;
                        }
                    }
                }
            }
        }

        return [$grid, $move];
    }

    public function move(array $grid, $facing = 'east')
    {
        $move = false;
        $done = [];
        $topState = $grid[0];
        for ($y = 0; $y < count($grid); ++$y) {
            $leftState = $grid[$y][0];
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if ('east' === $facing && !isset($done["$y-$x"])) {
                    if ('>' === $grid[$y][$x]) {
                        if (isset($grid[$y][$x + 1])) {
                            if ('.' === $grid[$y][$x + 1]) {
                                $grid[$y][$x + 1] = '>';
                                $grid[$y][$x] = '.';
                                $move = true;
                                $done["$y-".($x + 1)] = 0;
                            }
                        } elseif (isset($grid[$y][0]) && '.' === $leftState) {
                            $grid[$y][0] = '>';
                            $grid[$y][$x] = '.';
                            $move = true;
                            $done["$y-0"] = 0;
                        }
                    }
                }

                if ('south' === $facing && !isset($done["$y-$x"])) {
                    if ('v' === $grid[$y][$x]) {
                        if (isset($grid[$y + 1][$x])) {
                            if ('.' === $grid[$y + 1][$x]) {
                                $grid[$y + 1][$x] = 'v';
                                $grid[$y][$x] = '.';
                                $done[($y + 1)."-$x"] = 0;
                                $move = true;
                            }
                        } elseif (isset($grid[0][$x]) && '.' === $topState[$x]) {
                            $grid[0][$x] = 'v';
                            $grid[$y][$x] = '.';
                            $done["0-$x"] = 0;
                            $move = true;
                        }
                    }
                }
            }
        }

        return [$grid, $move];
    }
}
