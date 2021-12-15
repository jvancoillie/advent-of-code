<?php

namespace App\Puzzle\Year2021\Day15;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\PathFinding\Dijkstra;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 40;
    protected static int|string $testPart2Expected = 315;

    protected static int|string $part1Expected = 687;
    protected static int|string $part2Expected = 2957;

    public function part1()
    {
        return $this->pathfinding($this->createGrid($this->getInput()));
    }

    public function part2()
    {
        $grid = $this->multiply($this->createGrid($this->getInput()));

        return $this->pathfinding($grid);
    }

    private function pathfinding(array $grid)
    {
        $graph = $this->createGraph($grid);
        $endNode = sprintf('%s-%s', count($grid) - 1, count($grid[0]) - 1);

        $dijkstra = new Dijkstra($graph);
        $paths = $dijkstra->findPath('0-0', $endNode, true);

        return end($paths)['path_weight'];
    }

    // TODO improve this & refactor
    private function createGrid(PuzzleInput $input): array
    {
        $data = explode("\n", $input->getData());
        $grid = [];
        foreach ($data as $line) {
            $grid[] = str_split($line);
        }

        return $grid;
    }

    // TODO improve this & refactor
    private function createGraph($grid): array
    {
        $graph = [];
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                foreach ([[-1, 0], [1, 0], [0, 1], [0, -1]] as [$dy, $dx]) {
                    $ny = $y + $dy;
                    $nx = $x + $dx;
                    if (isset($grid[$ny][$nx])) {
                        $graph["$y-$x"]["$ny-$nx"] = $grid[$ny][$nx];
                    }
                }
            }
        }

        return $graph;
    }

    // TODO improve this & refactor
    private function multiply(array $grid): array
    {
        $lenY = count($grid);
        $lenX = count($grid);
        for ($y = 0; $y < $lenY; ++$y) {
            for ($x = 0; $x < $lenX; ++$x) {
                $n = $grid[$y][$x];
                for ($i = 1; $i < 5; ++$i) {
                    $ny = $y + $lenY * $i;
                    ++$n;
                    if ($n > 9) {
                        $n = 1;
                    }
                    $grid[$ny][$x] = $n;
                }
            }
        }
        $lenY = count($grid);
        $lenX = count($grid[0]);
        for ($y = 0; $y < $lenY; ++$y) {
            for ($x = 0; $x < $lenX; ++$x) {
                $n = $grid[$y][$x];
                for ($i = 1; $i < 5; ++$i) {
                    $nx = $x + $lenX * $i;
                    ++$n;
                    if ($n > 9) {
                        $n = 1;
                    }
                    $grid[$y][$nx] = $n;
                }
            }
        }

        return $grid;
    }
}
