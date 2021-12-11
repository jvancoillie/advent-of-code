<?php

namespace App\Puzzle\Year2016\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3;
    protected static int|string $testPart2Expected = 6;

    protected static int|string $part1Expected = 862;
    protected static int|string $part2Expected = 1577;

    public function part1()
    {
        $triangles = [];
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $triangles[] = array_map('intval', preg_split("/[\s]+/", trim($line)));
        }

        return $this->countValidTriangles($triangles);
    }

    public function part2()
    {
        $cols = [[], [], []];
        $triangles = [];
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            [$col1, $col2, $col3] = array_map('intval', preg_split("/[\s]+/", trim($line)));
            $cols[0][] = $col1;
            $cols[1][] = $col2;
            $cols[2][] = $col3;
            if (3 === count($cols[0])) {
                foreach ($cols as $col) {
                    $triangles[] = $col;
                }
                $cols = [[], [], []];
            }
        }

        return $this->countValidTriangles($triangles);
    }

    /**
     * @param int[][] $triangles
     *
     * @psalm-param list<list<int>> $triangles
     *
     * @psalm-return 0|positive-int
     */
    private function countValidTriangles(array $triangles): int
    {
        $valid = 0;

        foreach ($triangles as $triangle) {
            sort($triangle);
            if ($triangle[0] + $triangle[1] > $triangle[2]) {
                ++$valid;
            }
        }

        return $valid;
    }
}
