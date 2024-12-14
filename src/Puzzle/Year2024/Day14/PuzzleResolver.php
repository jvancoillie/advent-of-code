<?php

namespace App\Puzzle\Year2024\Day14;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 12;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 229868730;
    protected static int|string $part2Expected = 7861;

    public function part1(): int
    {
        $data = $this->getInput()->getArrayData();

        $size = $this->isTestMode() ? [11, 7] : [101, 103];
        $time = 100;
        $robots = $this->parseRobots($data);

        for ($i = 0; $i < $time; ++$i) {
            foreach ($robots as $key => $robot) {
                $robots[$key] = $this->moveRobot($robot, $size);
            }
        }

        return $this->calculateSafetyFactor($robots, $size);
    }

    public function part2(): int
    {
        if ($this->isTestMode()) {
            throw new \RuntimeException('No easter egg in test mode !');
        }

        $pattern = [
            [0, 0], [1, 0], [2, 0],
            [0, 1], [1, 1], [2, 1],
            [0, 2], [1, 2], [2, 2],
        ];

        $data = $this->getInput()->getArrayData();
        $size = [101, 103];
        $time = 0;
        $robots = $this->parseRobots($data);

        while (true) {
            ++$time;
            foreach ($robots as $key => $robot) {
                $robots[$key] = $this->moveRobot($robot, $size);
            }

            if ($this->hasPattern($robots, $pattern)) {
                $this->dump($robots, $size);
                break;
            }

            if ($time > 10000) {
                throw new \RuntimeException('Tree not found after 10000 seconds');
            }
        }

        return $time;
    }

    private function calculateSafetyFactor(array $robots, array $size): int
    {
        $midX = (int) ($size[0] / 2);
        $midY = (int) ($size[1] / 2);

        $quadrants = [0, 0, 0, 0]; // Q1, Q2, Q3, Q4
        foreach ($robots as [$pos, $vel]) {
            [$x, $y] = $pos;

            if ($x == $midX || $y == $midY) {
                // Ignorer les robots au centre
                continue;
            }

            // Identifier le quadrant
            if ($x > $midX && $y > $midY) {
                ++$quadrants[0]; // Q1
            } elseif ($x < $midX && $y > $midY) {
                ++$quadrants[1]; // Q2
            } elseif ($x < $midX && $y < $midY) {
                ++$quadrants[2]; // Q3
            } elseif ($x > $midX && $y < $midY) {
                ++$quadrants[3]; // Q4
            }
        }

        return array_product($quadrants);
    }

    private function parseRobots(array $data): array
    {
        $robots = [];
        foreach ($data as $line) {
            [$p, $v] = explode(' ', $line);
            [$px, $py] = array_map('intval', explode(',', explode('=', $p)[1]));
            [$vx, $vy] = array_map('intval', explode(',', explode('=', $v)[1]));
            $robots[] = [[$px, $py], [$vx, $vy]];
        }

        return $robots;
    }

    private function moveRobot(array $robot, array $size): array
    {
        [$px, $py] = $robot[0];
        [$vx, $vy] = $robot[1];

        $nx = ($px + $vx) % $size[0];
        $ny = ($py + $vy) % $size[1];

        if ($nx < 0) {
            $nx += $size[0];
        }
        if ($ny < 0) {
            $ny += $size[1];
        }

        return [[$nx, $ny], [$vx, $vy]];
    }

    private function hasPattern(array $robots, array $pattern): bool
    {
        $positions = array_column($robots, 0);
        $robotMap = [];

        foreach ($positions as [$x, $y]) {
            $robotMap["$x,$y"] = true;
        }

        foreach ($positions as [$x, $y]) {
            $matches = true;
            foreach ($pattern as [$dx, $dy]) {
                if (!isset($robotMap[($x + $dx).','.($y + $dy)])) {
                    $matches = false;
                    break;
                }
            }
            if ($matches) {
                return true;
            }
        }

        return false;
    }

    private function dump(array $robots, array $size): void
    {
        $grid = Grid::create($size[0], $size[1], '.');

        foreach ($robots as $robot) {
            [$x, $y] = $robot[0];
            $grid[$y][$x] = 'X';
        }

        Grid::dump($grid, '');
    }
}
