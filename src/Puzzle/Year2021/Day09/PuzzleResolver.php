<?php

namespace App\Puzzle\Year2021\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 15;
    protected static int|string $testPart2Expected = 1134;

    protected static int|string $part1Expected = 448;
    protected static int|string $part2Expected = 1417248;

    private array $grid = [];
    private array $directions = [
        [0, 1],
        [0, -1],
        [-1, 0],
        [1, 0],
    ];
    private array $visited = [];
    private array $basinLengths = [];
    private array $lowPoints = [];

    public function initialize(): void
    {
        $this->createGrid(explode("\n", $this->getInput()->getData()));

        $this->browse();
    }

    public function part1(): int
    {
        return (int) array_sum($this->lowPoints) + count($this->lowPoints);
    }

    public function part2(): int
    {
        return (int) array_product(array_slice($this->basinLengths, 0, 3));
    }

    private function createGrid($data): void
    {
        foreach ($data as $line) {
            $this->grid[] = array_map('intval', str_split($line));
        }
    }

    public function browse(): void
    {
        for ($y = 0; $y < count($this->grid); ++$y) {
            for ($x = 0; $x < count($this->grid[$y]); ++$x) {
                $currentValue = $this->grid[$y][$x];
                $isLowest = true;
                foreach ($this->directions as [$dy, $dx]) {
                    $nx = $dx + $x;
                    $ny = $dy + $y;

                    if (isset($this->grid[$ny][$nx]) && $this->grid[$ny][$nx] <= $currentValue) {
                        $isLowest = false;
                        break;
                    }
                }

                if ($isLowest) {
                    $this->visited = [];
                    $this->basinLengths[] = count($this->getbasin($x, $y));
                    $this->lowPoints[] = $currentValue;
                }
            }
        }
        rsort($this->basinLengths);
    }

    private function getBasin($x, $y, $done = []): array
    {
        $r = [$this->grid[$y][$x]];
        $this->visited[] = "$x-$y";

        foreach ($this->directions as [$dy, $dx]) {
            $nx = $dx + $x;
            $ny = $dy + $y;

            if (isset($this->grid[$ny][$nx]) && !in_array("$nx-$ny", $this->visited) && 9 !== $this->grid[$ny][$nx]) {
                $r = array_merge($r, $this->getBasin($nx, $ny, $done));
            }
        }

        return $r;
    }
}
