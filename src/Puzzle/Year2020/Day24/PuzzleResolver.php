<?php

namespace App\Puzzle\Year2020\Day24;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 10;
    protected static int|string $testPart2Expected = 2208;

    protected static int|string $part1Expected = 317;
    protected static int|string $part2Expected = 3804;

    public const BLACK = '#';
    public const WHITE = 'O';
    private $directions = [
        'w' => [-1, 0],
        'e' => [1,  0],
        'ne' => [0,  1],
        'nw' => [-1, 1],
        'se' => [1, -1],
        'sw' => [0, -1],
    ];
    private $minX = 0;
    private $minY = 0;
    private $maxX = 0;
    private $maxY = 0;
    private $floor = [];

    protected function initialize(): void
    {
        foreach (explode("\n", $this->getInput()->getData()) as $data) {
            $moves = $this->parseLine($data);
            $this->doMoves($moves);
        }
    }

    public function part1()
    {
        return $this->countBlackTiles();
    }

    public function part2()
    {
        for ($i = 0; $i < 100; ++$i) {
            $this->dayFlipping();
        }

        return $this->countBlackTiles();
    }

    /**
     * e, se, sw, w, nw, and ne.
     *
     * @return string[]
     *
     * @psalm-return list<string>
     */
    public function parseLine(string $string): array
    {
        $split = str_split($string);
        $moves = [];
        for ($i = 0; $i < count($split); ++$i) {
            $move = $split[$i];
            if ('s' === $split[$i] || 'n' === $split[$i]) {
                if (isset($split[$i + 1]) && ('e' === $split[$i + 1] || 'w' === $split[$i + 1])) {
                    $move .= $split[$i + 1];
                    ++$i;
                }
            }
            $moves[] = $move;
        }

        return $moves;
    }

    /**
     * @param string[] $moves
     *
     * @psalm-param list<string> $moves
     */
    public function doMoves(array $moves): void
    {
        $x = $y = 0;

        foreach ($moves as $move) {
            $x += $this->directions[$move][0];
            $y += $this->directions[$move][1];
        }

        $this->updateFloorSize($x, $y);

        if (isset($this->floor[$x][$y])) {
            $this->floor[$x][$y] = (self::BLACK === $this->floor[$x][$y]) ? self::WHITE : self::BLACK;
        } else {
            $this->floor[$x][$y] = self::BLACK;
        }
    }

    /**
     * @psalm-return 0|positive-int
     */
    public function countBlackTiles(): int
    {
        $sum = 0;
        foreach ($this->floor as $line) {
            foreach ($line as $tile) {
                if (self::BLACK === $tile) {
                    ++$sum;
                }
            }
        }

        return $sum;
    }

    public function dayFlipping(): void
    {
        $newFloor = [];

        $this->extendFloor();

        for ($x = $this->minX; $x <= $this->maxX; ++$x) {
            for ($y = $this->minY; $y <= $this->maxY; ++$y) {
                $colors = $this->countNeighborsColors($x, $y);
                $tile = $this->floor[$x][$y] ?? self::WHITE;
                if (self::WHITE === $tile && 2 === $colors[self::BLACK]) {
                    $newFloor[$x][$y] = self::BLACK;
                } elseif (self::BLACK === $tile && (0 === $colors[self::BLACK] || $colors[self::BLACK] > 2)) {
                    $newFloor[$x][$y] = self::WHITE;
                } else {
                    $newFloor[$x][$y] = $tile;
                }
            }
        }

        $this->floor = $newFloor;
    }

    /**
     * @return int[]
     */
    public function countNeighborsColors($x, $y): array
    {
        $colors = [self::BLACK => 0, self::WHITE => 0];
        foreach ($this->directions as [$ax, $ay]) {
            $nx = $x + $ax;
            $ny = $y + $ay;
            if (isset($this->floor[$nx][$ny])) {
                ++$colors[$this->floor[$nx][$ny]];
            } else {
                ++$colors[self::WHITE];
            }
        }

        return $colors;
    }

    public function extendFloor(): void
    {
        --$this->minX;
        ++$this->maxX;
        --$this->minY;
        ++$this->maxY;
    }

    private function updateFloorSize(int $x, int $y): void
    {
        if ($x > $this->maxX) {
            $this->maxX = $x;
        }
        if ($x < $this->minX) {
            $this->minX = $x;
        }
        if ($y > $this->maxY) {
            $this->maxY = $y;
        }
        if ($y < $this->minY) {
            $this->minY = $y;
        }
    }
}
