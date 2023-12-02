<?php

namespace App\Puzzle\Year2020\Day20;

use App\Utils\Grid;

class Puzzle
{
    private float $width;
    private array $solutions = [];

    public function __construct(private array $tiles)
    {
        $this->width = sqrt(count($tiles));
    }

    public function resolve(): int
    {
        foreach ($this->tiles as $currentTile) {
            foreach ($this->tiles as $tile) {
                if ($currentTile->id === $tile->id) {
                    continue;
                }
                $currentTile->setPossibleNeighbors($tile);
            }
        }

        $total = 1;

        foreach ($this->tiles as $tile) {
            if ($tile->isTopLeftCorner()) {
                foreach ($tile->getCornerTopLeftPositions() as $position) {
                    $grid = $this->fillGrid($tile, $position, Border::RIGHT);
                    $this->solutions[] = $grid;
                }
            }

            if ($tile->isCorner()) {
                $total *= $tile->id;
            }
        }

        return $total;
    }

    public function findSeaMonster($withDisplay = false): int
    {
        foreach ($this->solutions as $solution) {
            if (false !== $count = $this->checkSolution($solution, $withDisplay)) {
                return $count;
            }
        }

        return 0;
    }

    public function checkSolution($solution, $withDisplay): false|int
    {
        $grid = [];
        $start = 0;
        for ($y = 0; $y < $this->width; ++$y) {
            for ($x = 0; $x < $this->width; ++$x) {
                /**
                 * @var Tile $tile
                 */
                [$tile,$position] = $solution[$y][$x];
                $merge = $tile->getGridWithoutBorderByPosition($position);
                for ($i = 0; $i < count($merge); ++$i) {
                    $c = $grid[$i + $start] ?? '';
                    $grid[$i + $start] = trim($c.implode('', $merge[$i]));
                }
            }
            $start += 8;
        }
        foreach ($grid as $key => $line) {
            $grid[$key] = str_split($line);
        }

        if (false !== $points = $this->hasSeaMonster($grid)) {
            $this->replacePoints($grid, $points);
            if ($withDisplay) {
                Grid::dump($grid);
            }

            return $this->countHashtags($grid);
        }

        return false;
    }

    public function replacePoints(&$grid, $points): void
    {
        foreach ($points as [$y, $x]) {
            $grid[$y][$x] = 'O';
        }
    }

    public function countHashtags($grid): int
    {
        $total = 0;
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid); ++$x) {
                if ('#' === $grid[$y][$x]) {
                    ++$total;
                }
            }
        }

        return $total;
    }

    public function hasSeaMonster($grid): bool|array
    {
        $points = [];
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid); ++$x) {
                if (false !== $monsterPoints = $this->canDrawMonster($grid, $x, $y)) {
                    $points = array_merge($points, $monsterPoints);
                }
            }
        }

        return count($points) > 0 ? $points : false;
    }

    public function canDrawMonster($grid, $x, $y): bool|array
    {
        $points = [];

        foreach ($this->getSeaMonsterPattern() as [$dy, $dx]) {
            $nx = $x + $dx;
            $ny = $y + $dy;
            if (isset($grid[$ny][$nx]) && '#' === $grid[$ny][$nx]) {
                $points[] = [$ny, $nx];
            } else {
                return false;
            }
        }

        return $points;
    }

    /**
     * return point to draw SeaMonster
     *                    #
     *  #    ##    ##    ###
     *  #  #  #  #  #  #.
     */
    public function getSeaMonsterPattern(): array
    {
        return [
            [0, 18],
            [1, 0],
            [1, 5],
            [1, 6],
            [1, 11],
            [1, 12],
            [1, 17],
            [1, 18],
            [1, 19],
            [2, 1],
            [2, 4],
            [2, 7],
            [2, 10],
            [2, 13],
            [2, 16],
        ];
    }

    public function fillGrid(Tile $tile, $position, $direction, $x = 0, $y = 0, $grid = []): array
    {
        if (0 === $y && 0 === $x && !$tile->isTopLeftCorner($position)) {
            return $grid;
        }

        if (0 === $y && $x === ($this->width - 1) && !$tile->isTopLeftCorner($position)) {
            return $grid;
        }

        if ($y === ($this->width - 1) && 0 === $x && !$tile->isBottomLeftCorner($position)) {
            return $grid;
        }

        if ($y === ($this->width - 1) && $x === ($this->width - 1) && !$tile->isBottomRightCorner($position)) {
            return $grid;
        }

        $grid[$y][$x] = [$tile, $position];

        if ($x === ($this->width - 1) && $y === ($this->width - 1)) {
            return $grid;
        }

        if (Border::RIGHT === $direction) {
            ++$x;
        } elseif (Border::LEFT === $direction) {
            --$x;
        }

        if (0 === $x % $this->width && Border::RIGHT === $direction) {
            --$x;
            $direction = Border::BOTTOM;
            ++$y;
        } elseif (-1 === $x && Border::LEFT === $direction) {
            $x = 0;
            $direction = Border::BOTTOM;
            ++$y;
        } elseif (Border::BOTTOM === $direction) {
            if (0 === $y % 2) {
                $direction = Border::RIGHT;
                ++$x;
            } else {
                $direction = Border::LEFT;
                --$x;
            }
        }

        foreach ($tile->getNeighbor($position, $direction) as $n) {
            $grid = $this->fillGrid($n['tile'], $n['position'], $direction, $x, $y, $grid);
        }

        return $grid;
    }

    public function getTile($id): ?Tile
    {
        return $this->tiles[$id] ?? null;
    }
}
