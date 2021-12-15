<?php

namespace App\Puzzle\Year2016\Day13;

class Tile implements NodeInterface
{
    public function __construct(private int $x, private int $y, private $value)
    {
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getValue()
    {
        return $this->value;
    }
}
