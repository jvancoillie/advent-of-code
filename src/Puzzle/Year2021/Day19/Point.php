<?php

namespace App\Puzzle\Year2021\Day19;

class Point
{
    private int $x;
    private int $y;
    private int $z;
    private array $rotations = [];

    public const AXES = ['x', 'y', 'z'];

    public function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getZ(): int
    {
        return $this->z;
    }

    public function __toString()
    {
        return sprintf('%d, %d, %d', $this->x, $this->y, $this->z);
    }

    public function getPointAt($pos): Point
    {
        $rotatedPoints = $this->getRotations();

        return $rotatedPoints[$pos];
    }

    private function getRotations(): array
    {
        if (count($this->rotations) > 0) {
            return $this->rotations;
        }

        $x = $this->x;
        $y = $this->y;
        $z = $this->z;

        $this->rotations = [
            new self($x, $y, $z),
            new self($x, $z, -$y),
            new self($x, -$y, -$z),
            new self($x, -$z, $y),
            new self(-$x, -$y, $z),
            new self(-$x, $z, $y),
            new self(-$x, $y, -$z),
            new self(-$x, -$z, -$y),
            new self($y, -$x, $z),
            new self($y, $z, $x),
            new self($y, $x, -$z),
            new self($y, -$z, -$x),
            new self(-$y, $x, $z),
            new self(-$y, $z, -$x),
            new self(-$y, -$x, -$z),
            new self(-$y, -$z, $x),
            new self($z, $x, $y),
            new self($z, $y, -$x),
            new self($z, -$x, -$y),
            new self($z, -$y, $x),
            new self(-$z, -$x, $y),
            new self(-$z, $y, $x),
            new self(-$z, $x, -$y),
            new self(-$z, -$y, -$x),
        ];

        return $this->rotations;
    }

    public function sub(Point $point): Point
    {
        $x = $this->getX() - $point->getX();
        $y = $this->getY() - $point->getY();
        $z = $this->getZ() - $point->getZ();

        return new self($x, $y, $z);
    }

    public function add(Point $point): Point
    {
        $x = $this->getX() + $point->getX();
        $y = $this->getY() + $point->getY();
        $z = $this->getZ() + $point->getZ();

        return new self($x, $y, $z);
    }

    public function isEqualTo(Point $point): bool
    {
        return $this->getX() === $point->getX() && $this->getY() === $point->getY() && $this->getZ() === $point->getZ();
    }

    public function toArray(): array
    {
        return [
            'x' => $this->getX(),
            'y' => $this->getY(),
            'z' => $this->getZ(),
        ];
    }

    public function getName(): string
    {
        return sprintf('%d,%d,%d', $this->x, $this->y, $this->z);
    }
}
