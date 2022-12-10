<?php

namespace App\Puzzle\Year2022\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 88;
    protected static int|string $testPart2Expected = 36;

    protected static int|string $part1Expected = 5883;
    protected static int|string $part2Expected = 2367;

    public function part1()
    {
        $tailPositions = $this->getTailPostions($this->getInput()->getArrayData(), 2);

        return array_reduce($tailPositions, fn ($c, $e) => $c += count($e));
    }

    public function part2()
    {
        $tailPositions = $this->getTailPostions($this->getInput()->getArrayData(), 10);

        return array_reduce($tailPositions, fn ($c, $e) => $c += count($e));
    }

    public function getTailPostions(array $moves, int $ropeLength)
    {
        $tailPosition[0][0] = 'S';
        $rope = array_fill(0, $ropeLength, [0, 0]);

        foreach ($moves as $move) {
            [$dir, $length] = explode(' ', $move);

            for ($i = 0; $i < $length; ++$i) {
                switch ($dir) {
                    case 'R':
                        ++$rope[0][1];
                        break;
                    case 'L':
                        --$rope[0][1];
                        break;
                    case 'U':
                        --$rope[0][0];
                        break;
                    case 'D':
                        ++$rope[0][0];
                        break;
                }

                $this->moveRope($dir, $rope, $tailPosition);
            }
        }

        return $tailPosition;
    }

    public function getDirection($headX, $headY, $tailX, $tailY): array
    {
        [$x, $y] = [$headX - $tailX, $headY - $tailY];

        return [0 === $x ? 0 : $x / abs($x), 0 === $y ? 0 : $y / abs($y)];
    }

    public function moveRope(string $direction, array &$rope, array &$tailPosition, int $ropeIndex = 0): void
    {
        if ($ropeIndex === count($rope) - 1) {
            return;
        }

        $isTail = $ropeIndex + 2 === count($rope);

        [$headX, $headY] = $rope[$ropeIndex];
        [$tailX, $tailY] = $rope[$ropeIndex + 1];

        if ($this->shouldTailMove($headX, $headY, $tailX, $tailY)) {
            [$dx, $dy] = $this->getDirection($headX, $headY, $tailX, $tailY);
            $tailX += $dx;
            $tailY += $dy;
            $rope[$ropeIndex + 1] = [$tailX, $tailY];
            $this->moveRope($direction, $rope, $tailPosition, $ropeIndex + 1);
        }

        if ($isTail) {
            $tailPosition[$tailX][$tailY] = '#';
        }
    }

    public function shouldTailMove($headX, $headY, $tailX, $tailY): bool
    {
        foreach ([[0, 0], [1, 1], [-1, -1], [1, -1], [-1, 1], [1, 0], [-1, 0], [0, 1], [0, -1]] as [$dx, $dy]) {
            if ($headX === ($tailX + $dx) && $headY === ($tailY + $dy)) {
                return false;
            }
        }

        return true;
    }
}
