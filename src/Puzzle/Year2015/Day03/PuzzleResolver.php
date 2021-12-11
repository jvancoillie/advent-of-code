<?php

namespace App\Puzzle\Year2015\Day03;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 11;

    protected static int|string $part1Expected = 2592;
    protected static int|string $part2Expected = 2360;

    public function part1()
    {
        $x = $y = 0;
        $houses = [[0]];
        foreach (str_split($this->getInput()->getData()) as $d) {
            switch ($d) {
                case '<':
                    --$x;
                    break;
                case '>':
                    ++$x;
                    break;
                case '^':
                    --$y;
                    break;
                case 'v':
                    ++$y;
                    break;
            }
            if (isset($houses[$x][$y])) {
                ++$houses[$x][$y];
            } else {
                $houses[$x][$y] = 0;
            }
        }

        return $this->countHouses($houses);
    }

    public function part2()
    {
        $coords = [
            'santa' => ['x' => 0, 'y' => 0],
            'robo' => ['x' => 0, 'y' => 0],
        ];
        $houses = [[0]];
        foreach (str_split($this->getInput()->getData()) as $key => $d) {
            $turn = (0 === $key % 2) ? 'santa' : 'robo';
            switch ($d) {
                case '<':
                    --$coords[$turn]['x'];
                    break;
                case '>':
                    ++$coords[$turn]['x'];
                    break;
                case '^':
                    --$coords[$turn]['y'];
                    break;
                case 'v':
                    ++$coords[$turn]['y'];
                    break;
            }
            if (isset($houses[$coords[$turn]['x']][$coords[$turn]['y']])) {
                ++$houses[$coords[$turn]['x']][$coords[$turn]['y']];
            } else {
                $houses[$coords[$turn]['x']][$coords[$turn]['y']] = 0;
            }
        }

        return $this->countHouses($houses);
    }

    public function countHouses(array $houses): int
    {
        $count = 0;
        foreach ($houses as $house) {
            $count += count($house);
        }

        return $count;
    }
}
