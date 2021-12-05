<?php

namespace App\Puzzle\Year2021\Day05;

class Segment
{
    public function __construct(private int $x1, private int $y1, private int $x2, private int $y2)
    {
    }

    public function getPoints(bool $withDiagonals = false): array
    {
        $points = [];
        if ($this->x1 == $this->x2) {
            foreach (range($this->y1, $this->y2) as $y) {
                $points[] = [$this->x1, $y];
            }
        }

        if ($this->y1 === $this->y2) {
            foreach (range($this->x1, $this->x2) as $x) {
                $points[] = [$x, $this->y1];
            }
        }

        if ($withDiagonals && $this->x1 !== $this->x2 && $this->y1 !== $this->y2) {
            $dy = ($this->y1 < $this->y2) ? 1 : -1;
            $dx = ($this->x1 < $this->x2) ? 1 : -1;

            $x = $this->x1;
            $y = $this->y1;

            while ($this->x2 !== $x && $this->y2 !== $y) {
                $points[] = [$x, $y];
                $x += $dx;
                $y += $dy;
            }

            $points[] = [$this->x2, $this->y2];
        }

        return $points;
    }
}
