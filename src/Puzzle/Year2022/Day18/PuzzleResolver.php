<?php

namespace App\Puzzle\Year2022\Day18;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 64;
    protected static int|string $testPart2Expected = 58;

    protected static int|string $part1Expected = 4192;
    protected static int|string $part2Expected = 2520;

    private array $cubes = [];
    private array $range = [];
    private array $memory = [];

    public const DIRECTIONS = [
        [0, 0, 1],
        [0, 0, -1],
        [0, 1, 0],
        [0, -1, 0],
        [1, 0, 0],
        [-1, 0, 0],
    ];

    protected function initialize(): void
    {
        // array of cubs coords
        $this->cubes = array_map(fn ($e) => explode(',', $e), $this->getInput()->getArrayData());
        // min,max ranges for x, y, z
        $this->range = [[INF, 0], [INF, 0], [INF, 0]];

        foreach ($this->cubes as $cube) {
            $this->range[0][0] = min($this->range[0][0], $cube[0]);
            $this->range[0][1] = max($this->range[0][1], $cube[0]);

            $this->range[1][0] = min($this->range[1][0], $cube[1]);
            $this->range[1][1] = max($this->range[1][1], $cube[1]);

            $this->range[2][0] = min($this->range[2][0], $cube[2]);
            $this->range[2][1] = max($this->range[2][1], $cube[2]);
        }
    }

    public function part1(): int
    {
        $area = 0;

        foreach ($this->cubes as $cube) {
            $area += $this->countVisibleFaces($cube);
        }

        return $area;
    }

    public function part2(): int
    {
        $area = 0;

        foreach ($this->cubes as $cube) {
            $area += $this->countVisibleFaces($cube, true);
        }

        return $area;
    }

    public function countVisibleFaces(array $cube, $recurse = false): int
    {
        $visible = 0;

        foreach (self::DIRECTIONS as [$dx, $dy, $dz]) {
            $x = $cube[0] + $dx;
            $y = $cube[1] + $dy;
            $z = $cube[2] + $dz;

            // has a neighbor cube !
            if (in_array([$x, $y, $z], $this->cubes)) {
                continue;
            }

            // has an air neighbor trapped ?
            if ($recurse && !$this->isTrapped([$x, $y, $z])) {
                continue;
            }

            ++$visible;
        }

        return $visible;
    }

    private function isTrapped($cube): bool
    {
        $key = sprintf('%d-%d-%d', $cube[0], $cube[1], $cube[2]);
        if (isset($this->memory[$key])) {
            return $this->memory[$key];
        }

        $queue = new \SplQueue();
        $queue->enqueue($cube);
        $visited = [$key];
        $trapped = false;

        while ($queue->count() > 0) {
            $cube = $queue->dequeue();

            if ($this->isOutOfRange($cube)) {
                $trapped = true;
                break;
            }

            foreach (self::DIRECTIONS as [$dx, $dy, $dz]) {
                $neighbour = [$cube[0] + $dx, $cube[1] + $dy, $cube[2] + $dz];
                $neighbourKey = sprintf('%d-%d-%d', $neighbour[0], $neighbour[1], $neighbour[2]);
                if (!in_array($neighbourKey, $visited) && !in_array($neighbour, $this->cubes)) {
                    $visited[] = $neighbourKey;
                    $queue->enqueue($neighbour);
                }
            }
        }

        // add visited to memory
        foreach ($visited as $vKey) {
            $this->memory[$vKey] = $trapped;
        }

        return $trapped;
    }

    public function isOutOfRange(array $cube): bool
    {
        return !(
            $cube[0] >= $this->range[0][0] && $cube[0] <= $this->range[0][1] &&
            $cube[1] >= $this->range[1][0] && $cube[1] <= $this->range[1][1] &&
            $cube[2] >= $this->range[2][0] && $cube[2] <= $this->range[2][1])
        ;
    }
}
