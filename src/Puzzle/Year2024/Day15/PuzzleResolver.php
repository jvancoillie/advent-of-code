<?php

namespace App\Puzzle\Year2024\Day15;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 10092;
    protected static int|string $testPart2Expected = 9021;

    protected static int|string $part1Expected = 1552463;
    protected static int|string $part2Expected = 1554058;

    public const WALL = '#';
    public const BOX = 'O';
    public const WIDEBOX = ['[', ']'];
    public const ROBOT = '@';
    public const EMPTY = '.';

    public function part1(): int
    {
        [$map, $moves, $pos] = $this->initializeMapAndMoves();

        $map = $this->processMoves($map, $pos, $moves, [$this, 'moveBox']);

        return $this->calculateSum($map, self::BOX);
    }

    public function part2(): int
    {
        [$map, $moves, $pos] = $this->initializeMapAndMoves(true);

        $map = $this->processMoves($map, $pos, $moves, [$this, 'moveWideBox']);

        return $this->calculateSum($map, self::WIDEBOX[0]);
    }

    private function initializeMapAndMoves(bool $expanded = false): array
    {
        [$mapData, $moveData] = explode("\n\n", $this->getInput()->getData());
        $map = array_map('str_split', explode(PHP_EOL, $mapData));
        $moves = str_split(str_replace(["\n", "\r"], '', $moveData));

        if ($expanded) {
            $map = $this->expandMap($map);
        }

        $robotPosition = $this->findRobotPosition($map);
        $map[$robotPosition[0]][$robotPosition[1]] = self::EMPTY;

        return [$map, $moves, $robotPosition];
    }

    private function findRobotPosition(array $map): array
    {
        foreach ($map as $y => $row) {
            foreach ($row as $x => $cell) {
                if (self::ROBOT === $cell) {
                    return [$y, $x];
                }
            }
        }

        return [];
    }

    private function processMoves(array $map, array $pos, array $moves, callable $callable): array
    {
        foreach ($moves as $move) {
            $dir = $this->getDirection($move);
            $ny = $pos[0] + $dir[0];
            $nx = $pos[1] + $dir[1];

            if (self::WALL === $map[$ny][$nx]) {
                continue;
            }

            if (self::EMPTY === $map[$ny][$nx]) {
                $pos = [$ny, $nx];
                continue;
            }

            if ($newMap = $callable($map, [$ny, $nx], $dir)) {
                $map = $newMap;
                $pos = [$ny, $nx];
            }
        }

        return $map;
    }

    private function moveBox(array $map, array $pos, array $dir): array
    {
        [$y, $x] = $pos;
        if (self::BOX === $map[$y][$x]) {
            if ($newMap = $this->moveSimple($map, [$y, $x], $dir)) {
                $map = $newMap;
                $map[$y][$x] = self::EMPTY;

                return $map;
            }
        }

        return [];
    }

    private function moveWideBox(array $map, array $pos, array $dir): array
    {
        [$y, $x] = $pos;
        if (in_array($map[$y][$x], self::WIDEBOX)) {
            if (0 === $dir[0]) {
                if ($newMap = $this->moveSimple($map, [$y, $x], $dir, self::WIDEBOX)) {
                    $map = $newMap;
                    $map[$y][$x] = self::EMPTY;

                    return $map;
                }

                return [];
            }

            $boxPosition = $this->getWideBoxPosition($map, [$y, $x]);
            if ($newMap = $this->moveComplex($map, $boxPosition, $dir)) {
                $map = $newMap;
                $map[$y][$x] = self::EMPTY;

                return $map;
            }
        }

        return [];
    }

    private function moveSimple(array $map, array $boxPos, array $dir, array $boxPattern = [self::BOX]): array
    {
        [$by, $bx] = [$boxPos[0] + $dir[0], $boxPos[1] + $dir[1]];

        if (self::WALL === $map[$by][$bx]) {
            return [];
        }

        if (self::EMPTY === $map[$by][$bx]) {
            return $this->updateMap($map, $boxPos, [$by, $bx]);
        }

        if (in_array($map[$by][$bx], $boxPattern)) {
            $newMap = $this->moveSimple($map, [$by, $bx], $dir, $boxPattern);
            if ($newMap) {
                return $this->updateMap($newMap, $boxPos, [$by, $bx]);
            }
        }

        return [];
    }

    private function moveComplex(array $map, array $boxPos, array $dir, array $moves = []): array
    {
        [$a, $b] = $boxPos;
        [$dy, $dx] = $dir;
        [$ay, $ax] = $a;
        [$by, $bx] = $b;

        $nay = $ay + $dy;
        $nax = $ax + $dx;
        $nby = $by + $dy;
        $nbx = $bx + $dx;

        if (self::WALL === $map[$nay][$nax] || self::WALL === $map[$nby][$nbx]) {
            return [];
        }

        $nextBoxA = $this->getWideBoxPosition($map, [$nay, $nax]);
        $nextBoxB = $this->getWideBoxPosition($map, [$nby, $nbx]);

        if (self::EMPTY === $map[$nay][$nax] && self::EMPTY === $map[$nby][$nbx]) {
            return $this->updateMap($map, $a, [$nay, $nax], $b, [$nby, $nbx]);
        }

        if (self::EMPTY === $map[$nay][$nax]) {
            $map = $this->moveComplex($map, $nextBoxB, $dir, $moves);
        } elseif (self::EMPTY === $map[$nby][$nbx]) {
            $map = $this->moveComplex($map, $nextBoxA, $dir, $moves);
        } else {
            $map = $this->moveComplex($map, $nextBoxA, $dir, $moves);

            if ($map && $nextBoxA !== $nextBoxB) {
                $map = $this->moveComplex($map, $nextBoxB, $dir, $moves);
            }
        }

        return $map ? $this->updateMap($map, $a, [$nay, $nax], $b, [$nby, $nbx]) : [];
    }

    private function updateMap(array $map, array $a, array $na, array $b = [], array $nb = []): array
    {
        [$ay, $ax] = $a;
        [$nay, $nax] = $na;
        $map[$nay][$nax] = $map[$ay][$ax];
        $map[$ay][$ax] = self::EMPTY;

        if ($b && $nb) {
            [$by, $bx] = $b;
            [$nby, $nbx] = $nb;

            $map[$nby][$nbx] = $map[$by][$bx];
            $map[$by][$bx] = self::EMPTY;
        }

        return $map;
    }

    private function getDirection(mixed $move): array
    {
        return match ($move) {
            '>' => [0, 1],
            '<' => [0, -1],
            'v' => [1, 0],
            '^' => [-1, 0],
            default => [0, 0],
        };
    }

    private function calculateSum(array $map, string $pattern): int
    {
        $sum = 0;

        foreach ($map as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($cell === $pattern) {
                    $sum += 100 * $y + $x;
                }
            }
        }

        return $sum;
    }

    private function expandMap(array $map): array
    {
        $expanded = [];

        foreach ($map as $row) {
            $line = [];
            foreach ($row as $cell) {
                if (self::BOX === $cell) {
                    $line[] = self::WIDEBOX[0];
                    $line[] = self::WIDEBOX[1];
                    continue;
                }

                if (self::ROBOT === $cell) {
                    $line[] = $cell;
                    $line[] = self::EMPTY;
                    continue;
                }

                $line[] = $cell;
                $line[] = $cell;
            }
            $expanded[] = $line;
        }

        return $expanded;
    }

    private function getWideBoxPosition(array $map, array $pos): array
    {
        [$y, $x] = $pos;

        $isWideBoxStart = $map[$y][$x] === self::WIDEBOX[0];
        $neighborDir = $this->getDirection($isWideBoxStart ? '>' : '<');
        $npos = [$y + $neighborDir[0], $x + $neighborDir[1]];

        return $isWideBoxStart ? [$pos, $npos] : [$npos, $pos];
    }
}
