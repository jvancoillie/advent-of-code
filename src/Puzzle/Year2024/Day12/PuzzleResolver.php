<?php

namespace App\Puzzle\Year2024\Day12;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1930;
    protected static int|string $testPart2Expected = 1206;

    protected static int|string $part1Expected = 1522850;
    protected static int|string $part2Expected = 0;

    public function part1()
    {
        $ans = 0;

        $data = array_map('str_split', $this->getInput()->getArrayData());

        $regions = $this->createRegions($data);
        foreach ($regions as $region) {
            $perimeter = $this->calculatePerimeter($region['cells']);
            $count = count($region['cells']);
            $mul = $perimeter * $count;
            dump("A region of {$region['value']} plants with price {$count} * {$perimeter} = {$mul}");
            $ans += $perimeter * $count;
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        $data = array_map('str_split', $this->getInput()->getArrayData());
        $regions = $this->createRegions($data);

        foreach ($regions as $region) {
            $sides = $this->calculateSides($region['cells']);
            $count = count($region['cells']);
            $mul = $sides * $count;
            dump("A region of {$region['value']} plants with price {$count} * {$sides} = {$mul}");
            $ans += $mul;
        }

        return $ans;
    }

    private function createRegions(array $data): array
    {
        $height = count($data);
        $width = count($data[0]);
        $visited = [];
        $regions = [];

        foreach ($data as $y => $row) {
            foreach ($row as $x => $value) {
                if (isset($visited["$y,$x"])) {
                    continue; // Case déjà visitée
                }

                // Nouvelle région
                $region = [];
                $queue = new \SplQueue();
                $queue->enqueue([$y, $x]);

                while (!$queue->isEmpty()) {
                    [$cy, $cx] = $queue->dequeue();

                    // Si déjà visité ou différente valeur, on ignore
                    if (isset($visited["$cy,$cx"]) || $data[$cy][$cx] !== $value) {
                        continue;
                    }

                    $visited["$cy,$cx"] = true;
                    $region[] = [$cy, $cx];

                    // Vérifie les 4 directions (haut, bas, gauche, droite)
                    foreach (Grid::$crossDirections as [$dy, $dx]) {
                        $ny = $cy + $dy;
                        $nx = $cx + $dx;

                        if ($ny >= 0 && $ny < $height && $nx >= 0 && $nx < $width) {
                            $queue->enqueue([$ny, $nx]);
                        }
                    }
                }

                $regions[] = ['value' => $value, 'cells' => $region];
            }
        }

        return $regions;
    }

    private function calculatePerimeter(array $region): int
    {
        $perimeter = 0;
        foreach ($region as [$y, $x]) {
            foreach (Grid::$crossDirections as [$dy, $dx]) {
                $ny = $y + $dy;
                $nx = $x + $dx;
                if (!in_array([$ny, $nx], $region)) {
                    ++$perimeter;
                }
            }
        }

        return $perimeter;
    }

    private function calculateSides(array $region): int
    {
        $sides = 0;

        return $sides;
    }
}
