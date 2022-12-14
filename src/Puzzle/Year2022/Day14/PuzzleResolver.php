<?php

namespace App\Puzzle\Year2022\Day14;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 24;
    protected static int|string $testPart2Expected = 93;

    protected static int|string $part1Expected = 873;
    protected static int|string $part2Expected = 24813;

    public const AIR = '.';
    public const ROCK = '#';
    public const SAND = 'O';

    public const POURING_Y = 0;
    public const POURING_X = 500;

    public function part1(): int
    {
        [$maxY, $grid] = $this->parse();

        do {
            $initialGrid = $grid;
            $grid = $this->pouringSand($initialGrid, self::POURING_Y, self::POURING_X, $maxY);
        } while ($initialGrid !== $grid);

        return Grid::count($grid, self::SAND);
    }

    public function part2(): int
    {
        [$maxY, $grid] = $this->parse();

        do {
            $grid = $this->pouringSand($grid, self::POURING_Y, self::POURING_X, $maxY + 2, true);
        } while (!isset($grid[self::POURING_Y][self::POURING_X]));

        return Grid::count($grid, self::SAND);
    }

    private function drawRocks(array $grid, array $paths): array
    {
        for ($i = 0; $i < count($paths) - 1; ++$i) {
            $from = $paths[$i];
            $to = $paths[$i + 1];
            $startY = min($from[1], $to[1]);
            $endY = max($from[1], $to[1]);
            $startX = min($from[0], $to[0]);
            $endX = max($from[0], $to[0]);

            for ($y = $startY; $y <= $endY; ++$y) {
                for ($x = $startX; $x <= $endX; ++$x) {
                    $grid[$y][$x] = '#';
                }
            }
        }

        return $grid;
    }

    private function pouringSand(array $grid, int $y, int $x, int $maxY, bool $withFloor = false): array
    {
        $yPoint = 0;
        while ($yPoint++ <= $maxY) {
            if ($yPoint < $y) {
                continue;
            }
            if ($withFloor && $yPoint === $maxY) {
                $y = $yPoint - 1;
                $grid[$y][$x] = self::SAND;
                break;
            }

            if (isset($grid[$yPoint][$x])) {
                $y = $yPoint - 1;

                // check on the left
                if (!isset($grid[$yPoint][$x - 1])) {
                    // here new fall point;
                    return $this->pouringSand($grid, $yPoint, $x - 1, $maxY, $withFloor);
                }

                // check on the right
                if (!isset($grid[$yPoint][$x + 1])) {
                    // here new fall point;
                    return $this->pouringSand($grid, $yPoint, $x + 1, $maxY, $withFloor);
                }

                $grid[$y][$x] = self::SAND;

                return $grid;
            }
        }

        return $grid;
    }

    private function parse(): array
    {
        $maxY = 0;
        $grid = [];
        foreach ($this->getInput()->getArrayData() as $line) {
            $path = [];
            $entry = explode(' -> ', $line);
            foreach ($entry as $coords) {
                $c = array_map('intval', explode(',', $coords));
                $path[] = $c;
                $maxY = max($maxY, $c[1]);
            }

            $grid = $this->drawRocks($grid, $path);
        }

        return [$maxY, $grid];
    }
}
