<?php

namespace App\Puzzle\Year2023\Day10;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 80;
    protected static int|string $testPart2Expected = 10;

    protected static int|string $part1Expected = 6714;
    protected static int|string $part2Expected = 429;

    public const PIPES = [
        '|' => [[-1, 0], [1, 0]],
        '-' => [[0, -1], [0, 1]],
        'L' => [[-1, 0], [0, 1]],
        'J' => [[-1, 0], [0, -1]],
        '7' => [[0, -1], [1, 0]],
        'F' => [[0, 1], [1, 0]],
    ];

    protected array $grid = [];
    protected array $startAt = [];

    protected array $path = [];

    public function initialize(): void
    {
        $this->grid = array_map(fn ($e) => str_split($e), $this->getInput()->getArrayData());

        foreach ($this->grid as $x => $line) {
            if (false !== $y = array_search('S', $line)) {
                $this->startAt = [$x, $y];
            }
        }
    }

    public function part1(): int
    {
        [$x, $y] = $this->startAt;
        $path = [[$x, $y]];

        foreach (Grid::$crossDirections as [$dx, $dy]) {
            if ($this->isConnected($x, $y, $x + $dx, $y + $dy)) {
                $path[] = [$x + $dx, $y + $dy];
                break;
            }
        }

        $this->path = $this->walk($this->grid, $path);

        return count($this->path) / 2;
    }

    public function part2(): int
    {
        $ans = 0;
        $pathAssoc = [];
        foreach ($this->path as [$x, $y]) {
            $pathAssoc["$x|$y"] = 1;
        }

        foreach ($this->grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if (!isset($pathAssoc["$x|$y"])) {
                    $r = $this->isEnclose([$x, $y], $this->path);
                    if ($r) {
                        ++$ans;
                    }
                }
            }
        }

        return $ans;
    }

    public function walk($grid, $path)
    {
        while (true) {
            $lastKey = array_key_last($path);
            $currentNode = $path[$lastKey];
            $currentNodePipe = $grid[$currentNode[0]][$currentNode[1]];
            $previousNode = $path[$lastKey - 1];
            $previousDirection = [$previousNode[0] - $currentNode[0], $previousNode[1] - $currentNode[1]];

            $nextDirection = self::PIPES[$currentNodePipe][0] === $previousDirection ? self::PIPES[$currentNodePipe][1] : self::PIPES[$currentNodePipe][0];
            $nextNode = [$currentNode[0] + $nextDirection[0], $currentNode[1] + $nextDirection[1]];

            if ('S' === $grid[$nextNode[0]][$nextNode[1]]) {
                return $path;
            }

            $path[] = $nextNode;
        }
    }

    private function isConnected(int $x, int $y, int $nx, int $ny): bool
    {
        $pipe = $this->grid[$nx][$ny] ?? false;

        if (false === $pipe || !isset(self::PIPES[$pipe])) {
            return false;
        }

        foreach (self::PIPES[$pipe] as [$dx, $dy]) {
            if ($nx + $dx === $x && $ny + $dy === $y) {
                return true;
            }
        }

        return false;
    }

    public function isEnclose(array $point, array $path): bool
    {
        $x = $point[0];
        $y = $point[1];
        $count = count($path);
        $inside = false;

        for ($i = 0, $j = $count - 1; $i < $count; $j = $i++) {
            $xi = $path[$i][0];
            $yi = $path[$i][1];
            $xj = $path[$j][0];
            $yj = $path[$j][1];

            $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }
}
