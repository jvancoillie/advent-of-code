<?php

namespace App\Puzzle\Year2021\Day04;

class Board
{
    private const MARK = 'X';

    private array $grid = [];

    private int $lastNumber = -1;

    public function __construct(array $grid)
    {
        $this->grid = $grid;
    }

    public function play($number)
    {
        $this->lastNumber = $number;
        for ($y = 0; $y < count($this->grid); ++$y) {
            for ($x = 0; $x < count($this->grid); ++$x) {
                if ($this->grid[$y][$x] == $number) {
                    $this->grid[$y][$x] = self::MARK;
                }
            }
        }
    }

    public function win(): bool
    {
        return $this->hasFullLine($this->grid) || $this->hasFullLine(array_map(null, ...$this->grid));
    }

    private function hasFullLine($array): bool
    {
        for ($y = 0; $y < count($array); ++$y) {
            $values = array_count_values($array[$y]);
            if (isset($values[self::MARK]) && 5 === $values[self::MARK]) {
                return true;
            }
        }

        return false;
    }

    public function score(): int|float
    {
        $sum = 0;

        for ($y = 0; $y < count($this->grid); ++$y) {
            $sum += array_sum($this->grid[$y]);
        }

        return $sum * $this->lastNumber;
    }
}
