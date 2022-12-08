<?php

namespace App\Puzzle\Year2022\Day08;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 21;
    protected static int|string $testPart2Expected = 8;

    protected static int|string $part1Expected = 1870;
    protected static int|string $part2Expected = 517440;

    public const DIRECTIONS = [[0, 1], [0, -1], [1, 0], [-1, 0]];

    public function part1(): int
    {
        $data = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());
        $visibleTrees = [];

        for ($x = 0; $x < count($data); ++$x) {
            for ($y = 0; $y < count($data); ++$y) {
                $currentTree = $data[$x][$y];
                foreach (self::DIRECTIONS as [$dirX, $dirY]) {
                    $nx = $x + $dirX;
                    $ny = $y + $dirY;
                    $visible = true;
                    while (isset($data[$nx][$ny])) {
                        if ($data[$nx][$ny] >= $currentTree) {
                            $visible = false;
                            break;
                        }

                        $nx += $dirX;
                        $ny += $dirY;
                    }

                    if ($visible) {
                        $visibleTrees[] = [$x, $y];
                        break;
                    }
                }
            }
        }

        return count($visibleTrees);
    }

    public function part2(): int
    {
        $data = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());
        $max = 0;

        for ($x = 0; $x < count($data); ++$x) {
            for ($y = 0; $y < count($data); ++$y) {
                $currentTree = $data[$x][$y];
                $seen = [];
                foreach (self::DIRECTIONS as [$dirX, $dirY]) {
                    $nx = $x + $dirX;
                    $ny = $y + $dirY;
                    $seen["$dirX-$dirY"] = 0;
                    while (isset($data[$nx][$ny])) {
                        ++$seen["$dirX-$dirY"];

                        if ($data[$nx][$ny] >= $currentTree) {
                            break;
                        }

                        $nx += $dirX;
                        $ny += $dirY;
                    }
                }

                $scenic = (int) array_product($seen);
                if ($scenic > $max) {
                    $max = $scenic;
                }
            }
        }

        return $max;
    }
}
