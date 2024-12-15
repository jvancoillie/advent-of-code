<?php

namespace App\Puzzle\Year2024\Day15;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/15
 */
class PuzzleResolverCopy extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 0;
    protected static int|string $part2Expected = 0;

    public const WALL = '#';
    public const BOX = 'O';
    public const ROBOT = '@';
    public const EMPTY = '.';

    public function part1()
    {
        $ans = 0;

        [$mapData, $moveData] = explode("\n\n", $this->getInput()->getData());
        $map = array_map('str_split', explode(PHP_EOL, $mapData));
        $moves = str_split(str_replace(["\n", "\r"], '', $moveData));
        $pos = [];
        for ($y = 0; $y < count($map); ++$y) {
            for ($x = 0; $x < count($map[$y]); ++$x) {
                if (self::ROBOT == $map[$y][$x]) {
                    $map[$y][$x] = self::EMPTY;
                    $pos = [$y, $x];
                }
            }
        }

        $map = $this->move($map, $pos, $moves);

        return $this->sum($map);
    }

    public function part2()
    {
        return 0;
    }

    private function move(array $map, array $pos, array $moves)
    {
        foreach ($moves as $move) {
            $dir = $this->getDirection($move);
            $ny = $pos[0] + $dir[0];
            $nx = $pos[1] + $dir[1];

            if (self::WALL == $map[$ny][$nx]) {
                continue;
            }

            if (self::EMPTY == $map[$ny][$nx]) {
                $pos = [$ny, $nx];
                continue;
            }

            if (self::BOX == $map[$ny][$nx]) {
                $by = $ny;
                $bx = $nx;
                while (self::WALL != $map[$by][$bx]) {
                    $by += $dir[0];
                    $bx += $dir[1];
                    if (self::EMPTY == $map[$by][$bx]) {
                        $pos = [$ny, $nx];
                        $map[$by][$bx] = self::BOX;
                        $map[$ny][$nx] = self::EMPTY;
                        break;
                    }
                }
            }
        }
        dump("============move $move , Robot at : {$pos[0]}, {$pos[1]}===============");
        Grid::dump($map, '');

        return $map;
    }

    private function getDirection(mixed $move)
    {
        switch ($move) {
            case '>':
                return [0, 1];
            case '<':
                return [0, -1];
            case 'v':
                return [1, 0];
            case '^':
                return [-1, 0];
        }

        return [0, 0];
    }

    private function sum(array $map)
    {
        $sum = 0;
        for ($y = 0; $y < count($map); ++$y) {
            for ($x = 0; $x < count($map[$y]); ++$x) {
                if (self::BOX == $map[$y][$x]) {
                    $t = 100 * $y + $x;
                    //                    dump("$y,$x => $t");
                    $sum += $t;
                }
            }
        }

        return $sum;
    }

    private function reorder(mixed $map, array $pos, array $to, array $dir)
    {
        $y = $pos[0];
        $x = $pos[1];
        $toY = $to[0];
        $toX = $to[1];

        $value = '.';
        $y += $dir[0];
        $x += $dir[1];

        while (true) {
            $tmp = $map[$y][$x];
            $map[$y][$x] = $value;
            $value = $tmp;
            $y += $dir[0];
            $x += $dir[1];

            if ($y == $toY && $x == $toX) {
                $map[$y][$x] = $value;

                return $map;
            }
        }
    }
}
