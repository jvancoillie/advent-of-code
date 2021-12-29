<?php

namespace App\Puzzle\Year2020\Day20;

class Tile
{
    private array $positions = [];
    private array $neighbors = [];
    private array $grids = [];
    private ?int $width = null;

    /**
     * Tile constructor.
     */
    public function __construct(public $id, $data)
    {
        $this->createBorders($data);
        $this->createAllPositions();
    }

    public function createAllPositions()
    {
        $this->positions[Position::FRONT_ROTATE90] = $this->rotate90($this->positions[Position::FRONT]);
        $this->positions[Position::FRONT_ROTATE180] = $this->rotate90($this->positions[Position::FRONT_ROTATE90]);
        $this->positions[Position::FRONT_ROTATE270] = $this->rotate90($this->positions[Position::FRONT_ROTATE180]);

        $this->positions[Position::BACK] = $this->flip($this->positions[Position::FRONT]);
        $this->positions[Position::BACK_ROTATE90] = $this->rotate90($this->positions[Position::BACK]);
        $this->positions[Position::BACK_ROTATE180] = $this->rotate90($this->positions[Position::BACK_ROTATE90]);
        $this->positions[Position::BACK_ROTATE270] = $this->rotate90($this->positions[Position::BACK_ROTATE180]);

        $this->grids[Position::FRONT_ROTATE90] = $this->rotateGrid($this->grids[Position::FRONT]);
        $this->grids[Position::FRONT_ROTATE180] = $this->rotateGrid($this->grids[Position::FRONT_ROTATE90]);
        $this->grids[Position::FRONT_ROTATE270] = $this->rotateGrid($this->grids[Position::FRONT_ROTATE180]);

        $this->grids[Position::BACK] = $this->flipGrid($this->grids[Position::FRONT]);
        $this->grids[Position::BACK_ROTATE90] = $this->rotateGrid($this->grids[Position::BACK]);
        $this->grids[Position::BACK_ROTATE180] = $this->rotateGrid($this->grids[Position::BACK_ROTATE90]);
        $this->grids[Position::BACK_ROTATE270] = $this->rotateGrid($this->grids[Position::BACK_ROTATE180]);
    }

    public function createBorders($data)
    {
        $exploded = explode("\n", $data);

        $this->width = strlen($exploded[0]) - 1;
        $height = count($exploded) - 1;

        $leftBorder = '';
        $rightBorder = '';
        $grid = [];
        $borders = [];
        foreach ($exploded as $key => $line) {
            $split = str_split($line);
            $grid[] = $split;
            if (0 === $key) {
                $borders[Border::TOP] = $line;
            }
            if ($key === $height) {
                $borders[Border::BOTTOM] = $line;
            }

            $leftBorder .= $split[0];

            $rightBorder .= $split[$this->width];
        }
        $borders[Border::RIGHT] = $rightBorder;
        $borders[Border::LEFT] = $leftBorder;

        $this->grids[Position::FRONT] = $grid;
        $this->positions[Position::FRONT] = $borders;
    }

    public function flip($borders)
    {
        $flippedBorders = [];

        $flippedBorders[Border::TOP] = $borders[Border::BOTTOM];
        $flippedBorders[Border::BOTTOM] = $borders[Border::TOP];
        $flippedBorders[Border::LEFT] = strrev($borders[Border::LEFT]);
        $flippedBorders[Border::RIGHT] = strrev($borders[Border::RIGHT]);

        return $flippedBorders;
    }

    public function flipGrid($grid)
    {
        $flippedGrid = [];
        foreach ($grid as $key => $line) {
            $flippedGrid[$this->width - $key] = $line;
        }
        ksort($flippedGrid);

        return $flippedGrid;
    }

    public function rotate90($borders)
    {
        $rotatedBorders = [];

        $rotatedBorders[Border::TOP] = strrev($borders[Border::LEFT]);
        $rotatedBorders[Border::RIGHT] = $borders[Border::TOP];
        $rotatedBorders[Border::BOTTOM] = strrev($borders[Border::RIGHT]);
        $rotatedBorders[Border::LEFT] = $borders[Border::BOTTOM];

        return $rotatedBorders;
    }

    public function rotateGrid($grid)
    {
        array_unshift($grid, null);
        $grid = call_user_func_array('array_map', $grid);

        return array_map('array_reverse', $grid);
    }

    public function setPossibleNeighbors(Tile $tile)
    {
        foreach (Position::getPositions() as $currentTilePosition) {
            $currentBorders = $this->getBordersByPosition($currentTilePosition);
            foreach (Position::getPositions() as $position) {
                $tileBorders = $tile->getBordersByPosition($position);

                if ($currentBorders[Border::TOP] === $tileBorders[Border::BOTTOM]) {
                    $this->neighbors[$currentTilePosition][Border::TOP][] = [
                        'tile' => $tile,
                        'position' => $position,
                    ];
                }
                if ($currentBorders[Border::RIGHT] === $tileBorders[Border::LEFT]) {
                    $this->neighbors[$currentTilePosition][Border::RIGHT][] = [
                        'tile' => $tile,
                        'position' => $position,
                    ];
                }
                if ($currentBorders[Border::BOTTOM] === $tileBorders[Border::TOP]) {
                    $this->neighbors[$currentTilePosition][Border::BOTTOM][] = [
                        'tile' => $tile,
                        'position' => $position,
                    ];
                }
                if ($currentBorders[Border::LEFT] === $tileBorders[Border::RIGHT]) {
                    $this->neighbors[$currentTilePosition][Border::LEFT][] = [
                        'tile' => $tile,
                        'position' => $position,
                    ];
                }
            }
        }
    }

    public function getNeighbor($position, $direction)
    {
        return $this->neighbors[$position][$direction] ?? [];
    }

    public function isCorner()
    {
        return
            $this->isBottomLeftCorner() || $this->isBottomRightCorner()
            || $this->isTopLeftCorner() || $this->isTopRightCorner()
        ;
    }

    public function getCornerTopLeftPositions()
    {
        $positions = [];
        foreach ($this->neighbors as $position => $neigbors) {
            if (!isset($neigbors[Border::TOP]) && !isset($neigbors[Border::LEFT])) {
                $positions[] = $position;
            }
        }

        return $positions;
    }

    public function isTopLeftCorner($position = null)
    {
        if ($position) {
            foreach ($this->neighbors[$position] as $neigbors) {
                if (!isset($neigbors[Border::TOP]) && !isset($neigbors[Border::LEFT])) {
                    return true;
                }
            }
        } else {
            foreach ($this->neighbors as $neigbors) {
                if (!isset($neigbors[Border::TOP]) && !isset($neigbors[Border::LEFT])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isTopRightCorner($position = null)
    {
        if ($position) {
            foreach ($this->neighbors[$position] as $neigbors) {
                if (!isset($neigbors[Border::TOP]) && !isset($neigbors[Border::RIGHT])) {
                    return true;
                }
            }
        } else {
            foreach ($this->neighbors as $neigbors) {
                if (!isset($neigbors[Border::TOP]) && !isset($neigbors[Border::RIGHT])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isBottomRightCorner($position = null)
    {
        if ($position) {
            foreach ($this->neighbors[$position] as $neigbors) {
                if (!isset($neigbors[Border::BOTTOM]) && !isset($neigbors[Border::RIGHT])) {
                    return true;
                }
            }
        } else {
            foreach ($this->neighbors as $neigbors) {
                if (!isset($neigbors[Border::BOTTOM]) && !isset($neigbors[Border::RIGHT])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isBottomLeftCorner($position = null)
    {
        if ($position) {
            foreach ($this->neighbors[$position] as $neigbors) {
                if (!isset($neigbors[Border::BOTTOM]) && !isset($neigbors[Border::LEFT])) {
                    return true;
                }
            }
        } else {
            foreach ($this->neighbors as $neigbors) {
                if (!isset($neigbors[Border::BOTTOM]) && !isset($neigbors[Border::LEFT])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getBordersByPosition($position)
    {
        return $this->positions[$position];
    }

    public function getGridWithoutBorderByPosition($position)
    {
        return $this->removeBorders($this->grids[$position]);
    }

    public function removeBorders($grid)
    {
        $withoutBorders = [];
        for ($y = 1; $y < count($grid) - 1; ++$y) {
            for ($x = 1; $x < count($grid) - 1; ++$x) {
                $withoutBorders[$y - 1][$x - 1] = $grid[$y][$x];
            }
        }

        return $withoutBorders;
    }
}
