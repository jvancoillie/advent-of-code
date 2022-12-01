<?php

namespace App\Puzzle\Year2021\Day19;

class Scanner implements \Stringable
{
    private array $points = [];
    private Point $position;
    private int $pointDirection;
    private bool $locked = false;
    private array $vector = [0, 0, 0];
    private array $memory = [];

    public function __construct(private int $id)
    {
        $this->position = new Point(0, 0, 0);
        $this->pointDirection = -1;

        if (0 === $id) {
            $this->pointDirection = 0;
            $this->locked = true;
        }
    }

    public function addPoint(Point $point)
    {
        $this->points[] = $point;
    }

    /**
     * @return Point[]|array
     */
    public function getPointsAt($pos): array
    {
        $points = [];
        foreach ($this->points as $point) {
            $points[] = $point->getPointAt($pos);
        }

        return $points;
    }

    /**
     * @return Point[]|array
     *
     * @throws \Exception
     */
    public function getPoints(): array
    {
        if ($this->locked) {
            return $this->getPointsAt($this->pointDirection);
        }

        throw new \Exception("Scanner is not locked, can't retrieve relative points");
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }

    public function getPointsFromPosition(): array
    {
        $points = [];
        foreach ($this->getPoints() as $point) {
            $points[] = $point->add($this->position);
        }

        return $points;
    }

    public function setLocked(bool $locked): Scanner
    {
        $this->locked = $locked;

        return $this;
    }

    public function getVector(): array
    {
        return $this->vector;
    }

    public function setVector(array $vector): Scanner
    {
        $this->vector = $vector;

        return $this;
    }

    public function getPosition(): Point
    {
        return $this->position;
    }

    public function setPosition(Point $position): Scanner
    {
        $this->position = $position;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPointDirection(): int
    {
        return $this->pointDirection;
    }

    public function setPointDirection(int $pointDirection): Scanner
    {
        $this->pointDirection = $pointDirection;

        return $this;
    }

    public function getMemory(): array
    {
        return $this->memory;
    }

    public function addMemory($id): Scanner
    {
        $this->memory[] = $id;

        return $this;
    }

    public function hasMemory($id): bool
    {
        return in_array($id, $this->getMemory());
    }

    public function tryAlign(Scanner $scanner): bool
    {
        if ($this->hasMemory($scanner->getId())) {
            return false;
        }

        $currentPoints = $this->getPoints();
        // check each position of scanner ...
        foreach (range(0, 23) as $direction) {
            // get points at given pos
            $points = $scanner->getPointsAt($direction);

            foreach ($points as $pointA) {
                foreach ($currentPoints as $currentPointA) {
                    // keep this distance reference
                    $subPoint = $currentPointA->sub($pointA);

                    // Checks that at least 12 beacons are overlapping
                    $overlapping = 0;
                    foreach ($points as $pointB) {
                        $compareTo = $pointB->add($subPoint);

                        foreach ($currentPoints as $currentPointB) {
                            if ($currentPointB->isEqualTo($compareTo)) {
                                ++$overlapping;
                            }

                            if (12 === $overlapping) {
                                $scanner->setPosition($subPoint->add($this->getPosition()));
                                $scanner->setLocked(true);
                                $scanner->setPointDirection($direction);

                                return true;
                            }
                        }
                    }
                }
            }
        }

        $this->addMemory($scanner->getId());

        return false;
    }

    public function __toString(): string
    {
        return sprintf('id : %d, locked: %d, position: %d', $this->id, $this->locked, $this->position);
    }
}
