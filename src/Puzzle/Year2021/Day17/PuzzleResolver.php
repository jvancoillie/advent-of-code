<?php

namespace App\Puzzle\Year2021\Day17;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/17
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 45;
    protected static int|string $testPart2Expected = 112;

    protected static int|string $part1Expected = 19503;
    protected static int|string $part2Expected = 5200;

    private int $maxHeight = 0;
    private array $initialVelocities = [];

    public function initialize(): void
    {
        $data = explode(' ', $this->getInput()->getData());
        $x = array_map('intval', explode('..', substr($data[2], 2, -1)));
        $y = array_map('intval', explode('..', substr($data[3], 2)));

        $target = [$x, $y];

        for ($vx = 0; $vx < $target[0][1] + 1; ++$vx) {
            for ($vy = $target[1][0] - 1; $vy < abs($target[1][0]) + 1; ++$vy) {
                $max = 0;
                $position = [0, 0, $vx, $vy];
                $positions[] = $position;
                while (true) {
                    $position = $this->nextProbPosition($position);
                    $max = max($max, $position[1]);

                    if ($this->targeted($position, $target)) {
                        $this->initialVelocities[] = "$vx-$vy";
                        $this->maxHeight = max($max, $this->maxHeight);
                        break;
                    }

                    if ($this->overshoot($position, $target)) {
                        break;
                    }
                }
            }
        }
    }

    public function part1(): int
    {
        return $this->maxHeight;
    }

    public function part2(): int
    {
        return count($this->initialVelocities);
    }

    public function nextProbPosition(array $entry): array
    {
        [$posX, $posY, $vX, $vY] = $entry;

        $posX += $vX;
        $posY += $vY;

        if (0 !== $vX) {
            $vX += ($vX > 0) ? -1 : 1;
        }

        --$vY;

        return [$posX, $posY, $vX, $vY];
    }

    public function targeted($position, $target): bool
    {
        [$posX, $posY] = $position;

        return $posX >= $target[0][0] && $posX <= $target[0][1] && $posY >= $target[1][0] && $posY <= $target[1][1];
    }

    public function overshoot($position, $target): bool
    {
        [$posX, $posY] = $position;

        return $posX > $target[0][1] || $posY < $target[1][0];
    }
}
