<?php

namespace App\Puzzle\Year2020\Day12;

class Navigation
{
    private int $x = 0;
    private int $y = 0;

    private int $waypointX = 10;
    private int $waypointY = 1;

    private int $degree = 90;

    public function __construct(private $instructions)
    {
    }

    public function navigate($withWaypoint = false): int
    {
        $this->x = $this->y = 0;
        foreach ($this->instructions as [$d, $v]) {
            switch ($d) {
                case 'N':
                    if ($withWaypoint) {
                        $this->waypointY += $v;
                    } else {
                        $this->y -= $v;
                    }
                    break;
                case 'S':
                    if ($withWaypoint) {
                        $this->waypointY -= $v;
                    } else {
                        $this->y += $v;
                    }
                    break;
                case 'E':
                    if ($withWaypoint) {
                        $this->waypointX += $v;
                    } else {
                        $this->x += $v;
                    }
                    break;
                case 'W':
                    if ($withWaypoint) {
                        $this->waypointX -= $v;
                    } else {
                        $this->x -= $v;
                    }
                    break;
                case 'F':
                    if ($withWaypoint) {
                        $this->forwardToWaypoint($v);
                    } else {
                        $this->forward($v);
                    }

                    break;
                case 'L':
                case 'R':
                    if ($withWaypoint) {
                        $this->rotateWaypoint($d, $v);
                    } else {
                        $this->rotate($d, $v);
                    }
                    break;
            }
        }

        return $this->manhattan();
    }

    public function forward($dist): void
    {
        switch ($this->degree) {
            case 0:
                $this->y -= $dist;
                break;
            case 90:
                $this->x += $dist;
                break;
            case 180:
                $this->y += $dist;
                break;
            case 270:
                $this->x -= $dist;
                break;
        }
    }

    public function forwardToWaypoint($dist): void
    {
        $this->y += $this->waypointY * $dist;
        $this->x += $this->waypointX * $dist;
    }

    public function rotate($dir, $value): void
    {
        if ('L' === $dir) {
            $this->degree -= $value;
        } else {
            $this->degree += $value;
        }
        $this->degree = (360 + $this->degree) % 360;
    }

    public function rotateWaypoint($dir, $value)
    {
        // rotate the way point
        // 90 CW   (x, y) => (y, -x)
        // 90 CCW  (x, y) => (-y, x)

        for ($i = 0; $i < $value / 90; ++$i) {
            if ('L' === $dir) {
                $x = $this->waypointX;
                $this->waypointX = -$this->waypointY;
                $this->waypointY = $x;
            } else {
                $x = $this->waypointX;
                $this->waypointX = $this->waypointY;
                $this->waypointY = -$x;
            }
        }
    }

    public function manhattan(): int
    {
        return abs($this->x) + abs($this->y);
    }

    public function manhattanWaypoint(): int
    {
        return abs($this->waypointX) + abs($this->waypointY);
    }
}
