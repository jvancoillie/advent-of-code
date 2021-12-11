<?php

namespace App\Puzzle\Year2021\Day11;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1656;
    protected static int|string $testPart2Expected = 195;

    protected static int|string $part1Expected = 1675;
    protected static int|string $part2Expected = 515;

    private array $directions = [
        [0, 1],
        [0, -1],
        [1, 0],
        [1, -1],
        [1, 1],
        [-1, 0],
        [-1, -1],
        [-1, 1],
    ];

    public function part1(): int
    {
        $ans = 0;

        $data = explode("\n", $this->getInput()->getData());
        $grid = [];

        foreach ($data as $line) {
            $grid[] = str_split($line);
        }

        for ($i = 0; $i < 100; ++$i) {
            [$grid, $flashesCount] = $this->playStep($grid);
            $ans += $flashesCount;
        }

        return $ans;
    }

    public function part2(): int
    {
        $data = explode("\n", $this->getInput()->getData());

        $grid = [];

        foreach ($data as $line) {
            $grid[] = str_split($line);
        }

        $ans = 0;
        while (true) {
            ++$ans;
            [$grid, $flashesCount] = $this->playStep($grid);

            $sum = 0;
            foreach ($grid as $line) {
                $sum += array_sum($line);
            }
            if (0 == $sum) {
                break;
            }
        }

        return $ans;
    }

    public function playStep($grid): array
    {
        $flashes = [];
        $flashesCount = 0;

        // first increase energy
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if (9 == $grid[$y][$x]) {
                    $flashes[] = [$y, $x];
                    $grid[$y][$x] = 0;

                    continue;
                }

                ++$grid[$y][$x];
            }
        }
        // play flashes
        while (count($flashes) > 0) {
            [$y, $x] = array_pop($flashes);
            ++$flashesCount;
            foreach ($this->directions as [$dy, $dx]) {
                $nx = $x + $dx;
                $ny = $y + $dy;
                if (isset($grid[$ny][$nx]) && 0 != $grid[$ny][$nx]) {
                    ++$grid[$ny][$nx];

                    if ($grid[$ny][$nx] > 9) {
                        $grid[$ny][$nx] = 0;
                        $flashes[] = [$ny, $nx];
                    }
                }
            }
        }

        return [$grid, $flashesCount];
    }
}
