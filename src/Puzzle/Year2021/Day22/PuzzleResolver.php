<?php

namespace App\Puzzle\Year2021\Day22;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/22
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 474140;
    protected static int|string $testPart2Expected = 2758514936282235;

    protected static int|string $part1Expected = 620241;
    protected static int|string $part2Expected = 1284561759639324;

    public function part1(): int
    {
        $cubes = [];
        $actions = $this->parseInput($this->getInput(), true);

        foreach ($actions as [$state, $coords]) {
            for ($x = $coords[0][0]; $x <= $coords[0][1]; ++$x) {
                for ($y = $coords[1][0]; $y <= $coords[1][1]; ++$y) {
                    for ($z = $coords[2][0]; $z <= $coords[2][1]; ++$z) {
                        $cubes["$x,$y,$z"] = $state;
                    }
                }
            }
        }

        return count(array_filter($cubes, fn ($state) => $state));
    }

    public function part2(): int
    {
        $actions = $this->parseInput($this->getInput());

        return $this->playActions($actions);
    }

    private function playActions($actions): int
    {
        $cubes = [];

        foreach ($actions as $entry) {
            [$currentState, $currentCoords] = $entry;
            $newCubes = [];

            foreach ($cubes as $cube) {
                [$state, $coords] = $cube;
                if ($overlap = $this->getOverlap($currentCoords, $coords)) {
                    $newCubes[] = [!$state, $overlap];
                }
            }

            if ($currentState) {
                $cubes[] = $entry;
            }

            $cubes = [...$cubes, ...$newCubes];
        }

        $on = 0;
        foreach ($cubes as [$state, $coords]) {
            $volume = $this->getVolume($coords);
            $on += ($state) ? $volume : -$volume;
        }

        return $on;
    }

    private function parseInput(PuzzleInput $input, $limit = false): array
    {
        $actions = [];
        foreach ($input->getArrayData() as $line) {
            [$state, $coords] = explode(' ', $line);
            $entry = [];
            $correct = true;
            foreach (explode(',', $coords) as $data) {
                [$axe, $coord] = explode('=', $data);

                [$from, $to] = array_map('intval', explode('..', $coord));

                if ($limit) {
                    if ($from < -50) {
                        $from = -50;
                    }
                    if ($to > 50) {
                        $to = 50;
                    }

                    if ($from > $to) {
                        $correct = false;
                        break;
                    }
                }

                $entry[] = [$from, $to];
            }

            if ($correct) {
                $actions[] = ['on' === $state, $entry];
            }
        }

        return $actions;
    }

    private function getVolume($coordinates): int
    {
        return ($coordinates[0][1] - $coordinates[0][0] + 1) * ($coordinates[1][1] - $coordinates[1][0] + 1) * ($coordinates[2][1] - $coordinates[2][0] + 1);
    }

    private function getOverlap($cubeA, $cubeB): array
    {
        $overlap = [];

        if ($cubeB[0][0] <= $cubeA[0][1] && $cubeB[0][1] >= $cubeA[0][0] && $cubeB[1][0] <= $cubeA[1][1] && $cubeB[1][1] >= $cubeA[1][0] && $cubeB[2][0] <= $cubeA[2][1] && $cubeB[2][1] >= $cubeA[2][0]) {
            $overlap = [
                [max($cubeB[0][0], $cubeA[0][0]), min($cubeB[0][1], $cubeA[0][1])],
                [max($cubeB[1][0], $cubeA[1][0]), min($cubeB[1][1], $cubeA[1][1])],
                [max($cubeB[2][0], $cubeA[2][0]), min($cubeB[2][1], $cubeA[2][1])],
            ];
        }

        return $overlap;
    }
}
