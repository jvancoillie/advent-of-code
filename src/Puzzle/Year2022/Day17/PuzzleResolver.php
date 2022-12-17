<?php

namespace App\Puzzle\Year2022\Day17;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3068;
    protected static int|string $testPart2Expected = 1514285714288;

    protected static int|string $part1Expected = 3159;
    protected static int|string $part2Expected = 1566272189352;

    public const SHAPES = [
        [
            'size' => ['h' => 1, 'w' => 4],
            'shape' => [0 => [0 => '#', 1 => '#', 2 => '#', 3 => '#']],
        ],
        [
            'size' => ['h' => 3, 'w' => 3],
            'shape' => [
                -2 => [1 => '#'],
                -1 => [0 => '#', 1 => '#', 2 => '#'],
                0 => [1 => '#'],
            ],
        ],
        [
            'size' => ['h' => 3, 'w' => 3],
            'shape' => [
                -2 => [2 => '#'],
                -1 => [2 => '#'],
                0 => [0 => '#', 1 => '#', 2 => '#'],
            ],
        ],
        [
            'size' => ['h' => 4, 'w' => 1],
            'shape' => [
                -3 => [0 => '#'],
                -2 => [0 => '#'],
                -1 => [0 => '#'],
                0 => [0 => '#'],
            ],
        ],
        [
            'size' => ['h' => 1, 'w' => 4],
            'shape' => [
                -1 => [0 => '#', 1 => '#'],
                0 => [0 => '#', 1 => '#'],
            ],
        ],
    ];

    public function part1(): int
    {
        $grid = Grid::create(7, 4, '.');
        Grid::dump($grid);

        $jets = str_split($this->getInput()->getData());
        $rounds = 2022;

        $tall = 0;
        for ($i = 0; $i < $rounds; ++$i) {
            $tall = $this->play(self::SHAPES[$i % 5], $grid, $jets);
        }

        return $tall;
    }

    public function part2(): int
    {
        $grid = Grid::create(7, 4, '.');

        Grid::dump($grid);

        $jets = str_split($this->getInput()->getData());

        $rounds = 1000000000000;
        $tall = $i = $toAdd = 0;
        $states = [];

        while ($i < $rounds) {
            $tall = $this->play(self::SHAPES[$i % 5], $grid, $jets);

            $state = implode(array_map(fn ($row) => implode($row), array_slice($grid, -100)));
            $key = sprintf('%s-%d-%s', implode($jets), $i % 5, $state);

            if (isset($states[$key]) && $i >= count($jets) / 5) {
                $diffRounds = $i - $states[$key][0];
                $remaining = floor(($rounds - $i) / $diffRounds);
                $toAdd += $remaining * ($tall - $states[$key][1]);
                $i += $remaining * $diffRounds;
            }
            $states[$key] = [$i, $tall];
            ++$i;
        }

        return (int) ($tall + $toAdd);
    }

    public function play(array $shapeData, array &$grid, array &$jets): int
    {
        $floor = count($grid) - 1;
        $shape = $shapeData['shape'];
        $newPositionShape = [];

        // move shape to start position +2 Right
        foreach ($shape as $y => $row) {
            foreach ($row as $x => $v) {
                $nx = $x + 2;
                $newPositionShape[$y][$nx] = $v;
            }
        }

        $shape = $newPositionShape;

        $end = false;
        $loop = 0;
        while (!$end) {
            // here move left or right
            $direction = array_shift($jets);
            $jets[] = $direction;
            ++$loop;

            $newPositionShape = [];
            foreach ($shape as $y => $row) {
                foreach ($row as $x => $v) {
                    $nx = $x + ('>' === $direction ? 1 : -1);
                    if ($nx > 6 || $nx < 0) {
                        $newPositionShape = $shape;
                        break 2;
                    }
                    if (isset($grid[$y][$nx]) && '.' !== $grid[$y][$nx]) {
                        $newPositionShape = $shape;
                        break 2;
                    }
                    $newPositionShape[$y][$nx] = $v;
                }
            }

            $shape = $newPositionShape;

            // here down
            $newPositionShape = [];
            foreach ($shape as $y => $row) {
                foreach ($row as $x => $v) {
                    $ny = $y + 1;
                    if ($ny > $floor) {
                        $newPositionShape = $shape;
                        $end = true;
                        break 2;
                    }
                    if (isset($grid[$ny][$x]) && '.' !== $grid[$ny][$x]) {
                        $newPositionShape = $shape;
                        $end = true;
                        break 2;
                    }
                    $newPositionShape[$ny][$x] = $v;
                }
            }
            $shape = $newPositionShape;
        }

        $minY = 5;
        foreach ($shape as $y => $row) {
            $minY = min($minY, $y);
            foreach ($row as $x => $v) {
                $grid[$y][$x] = $v;
            }
        }

        // add new rows above
        $needed = 0;
        for ($i = 0; $i < 4; ++$i) {
            if (in_array('#', $grid[$i])) {
                $needed = 4 - $i;
                break;
            }
        }
        $tall = count($grid) - (4 - $needed);
        // add new rows above
        for ($i = 0; $i < $needed; ++$i) {
            array_unshift($grid, array_fill(0, 7, '.'));
        }

        return $tall;
    }
}
