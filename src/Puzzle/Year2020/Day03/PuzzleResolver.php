<?php

namespace App\Puzzle\Year2020\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 7;
    protected static int|string $testPart2Expected = 336;

    protected static int|string $part1Expected = 272;
    protected static int|string $part2Expected = 3898725600;

    public function part1(): int
    {
        $map = array_map('str_split', $this->getInput()->getArrayData());

        $slopes = [
            ['x' => 1, 'y' => 3],
        ];

        return $this->slide($slopes, $map);
    }

    public function part2(): int
    {
        $map = array_map('str_split', $this->getInput()->getArrayData());

        $slopes = [
            ['x' => 1, 'y' => 1],
            ['x' => 1, 'y' => 3],
            ['x' => 1, 'y' => 5],
            ['x' => 1, 'y' => 7],
            ['x' => 2, 'y' => 1],
        ];

        return $this->slide($slopes, $map);
    }

    protected function slide(array $slopes, array $map): int
    {
        $height = count($map) - 1;
        $width = count($map[0]) - 1;
        $total = 1;

        foreach ($slopes as $slope) {
            $x = $y = 0;
            $isOut = false;
            $treeCount = 0;
            while (!$isOut) {
                $x += $slope['x'];
                $y += $slope['y'];

                if ($y > $width) {
                    $y = ($y - $width) - 1;
                }
                if ('#' === $map[$x][$y]) {
                    ++$treeCount;
                }

                if ($x >= $height) {
                    $isOut = true;
                }
            }
            $total *= $treeCount;
        }

        return $total;
    }
}
