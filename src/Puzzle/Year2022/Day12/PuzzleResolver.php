<?php

namespace App\Puzzle\Year2022\Day12;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\PathFinding\Dijkstra;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 31;
    protected static int|string $testPart2Expected = 29;

    protected static int|string $part1Expected = 423;
    protected static int|string $part2Expected = 416;

    public const DIRECTIONS = [[-1, 0], [1, 0], [0, 1], [0, -1]];

    public function part1(): int
    {
        [$startNodes, $endNode, $graph] = $this->parseData();

        return $this->findFewestSteps($startNodes, $endNode, $graph);
    }

    public function part2(): int
    {
        [$startNodes, $endNode, $graph] = $this->parseData(true);

        return $this->findFewestSteps($startNodes, $endNode, $graph);
    }

    private function parseData($withAllStartPoints = false): array
    {
        $grid = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());
        $graph = [];
        $endNode = '';
        $startNodes = [];
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                $currentLetter = $grid[$y][$x];
                if ('S' === $currentLetter) {
                    $currentLetter = 'a';
                    $startNodes[] = sprintf('%s-%s', $y, $x);
                }

                if ($withAllStartPoints && 'a' === $grid[$y][$x]) {
                    $startNodes[] = sprintf('%s-%s', $y, $x);
                }

                if ('E' === $currentLetter) {
                    $currentLetter = 'z';
                    $endNode = sprintf('%s-%s', $y, $x);
                }

                foreach (self::DIRECTIONS as [$dy, $dx]) {
                    $ny = $y + $dy;
                    $nx = $x + $dx;

                    if (isset($grid[$ny][$nx])) {
                        $nextLetter = 'E' === $grid[$ny][$nx] ? 'z' : $grid[$ny][$nx];

                        if (ord($nextLetter) - ord($currentLetter) > 1) {
                            continue;
                        }

                        $graph["$y-$x"]["$ny-$nx"] = 1;
                    }
                }
            }
        }

        return [$startNodes, $endNode, $graph];
    }

    private function findFewestSteps(array $startNodes, string $endNode, array $graph): int
    {
        $dijkstra = new Dijkstra($graph);
        $minLength = INF;

        foreach ($startNodes as $startNode) {
            try {
                $paths = $dijkstra->findPath($startNode, $endNode, true);
                $minLength = min(end($paths)['path_weight'], $minLength);
            } catch (\Exception) {
            }
        }

        return $minLength;
    }
}
