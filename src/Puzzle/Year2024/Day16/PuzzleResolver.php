<?php

namespace App\Puzzle\Year2024\Day16;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public const START = 'S';
    public const END = 'E';
    public const WALL = '#';

    protected static int|string $testPart1Expected = 7036;
    protected static int|string $testPart2Expected = 45;

    protected static int|string $part1Expected = 91464;
    protected static int|string $part2Expected = 494;

    protected array $result = [];

    protected function initialize(): void
    {
        $maze = array_map('str_split', $this->getInput()->getArrayData());
        $start = [];
        for ($y = 0; $y < count($maze); ++$y) {
            for ($x = 0; $x < count($maze[$y]); ++$x) {
                if (self::START == $maze[$y][$x]) {
                    $start = [$y, $x];
                }
            }
        }

        $this->result = $this->findPath($start, $maze);
    }

    public function part1(): int
    {
        return $this->result['score'];
    }

    public function part2(): int
    {
        return count($this->getUniqueCoordinates($this->result['paths']));
    }

    /**
     * TODO optimize this, it take a while !!
     */
    private function findPath(array $start, array $grid): array
    {
        $queue = new \SplPriorityQueue();
        $queue->setExtractFlags(\SplPriorityQueue::EXTR_DATA);

        $scores = [];
        $startKey = "{$start[0]},{$start[1]}";
        $scores[$startKey] = 0;

        $bestScore = self::$part1Expected;
        $bestMoves = INF;
        $bestPaths = [];

        $queue->insert([$start, [0, 1], 0, 1, 0, [$start]], 0);

        while (!$queue->isEmpty()) {
            [$pos, $prevDir, $currentScore, $moves, $rotations, $path] = $queue->extract();
            [$y, $x] = $pos;

            if ($currentScore > $bestScore) {
                continue;
            }

            if ($moves > $bestMoves) {
                continue;
            }

            if (self::END === $grid[$y][$x]) {
                if ($currentScore < $bestScore) {
                    $bestScore = $currentScore;
                    $bestPaths = [$path];
                    $bestMoves = $moves;
                } elseif ($currentScore === $bestScore) {
                    $bestPaths[] = $path;
                }
                continue;
            }

            foreach ([[0, 1], [-1, 0], [1, 0], [0, -1]] as [$dy, $dx]) {
                $ny = $y + $dy;
                $nx = $x + $dx;

                if (isset($grid[$ny][$nx]) && self::WALL !== $grid[$ny][$nx]) {
                    $key = "$ny,$nx";

                    $newScore = $currentScore;
                    $newMoves = $moves;
                    $newRotations = $rotations;
                    $newDir = [$dy, $dx];

                    ++$newMoves;
                    if ($prevDir !== $newDir) {
                        $newScore += 1000;
                        ++$newRotations;
                    }

                    ++$newScore;

                    if (!isset($scores[$key]) || $newMoves <= $scores[$key]) {
                        $scores[$key] = $newMoves;
                        $newPath = [...$path, [$ny, $nx]];

                        $queue->insert([[$ny, $nx], $newDir, $newScore, $newMoves, $newRotations, $newPath], -$newScore);
                    }
                }
            }
        }

        return ['score' => $bestScore, 'paths' => $bestPaths];
    }

    private function getUniqueCoordinates(array $paths): array
    {
        $uniqueCoords = [];
        foreach ($paths as $path) {
            foreach ($path as [$y, $x]) {
                $uniqueCoords["$y,$x"] = [$y, $x];
            }
        }

        return array_values($uniqueCoords);
    }
}
