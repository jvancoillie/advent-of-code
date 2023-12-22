<?php

namespace App\Puzzle\Year2023\Day22;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/22
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 5;
    protected static int|string $testPart2Expected = 7;

    protected static int|string $part1Expected = 403;
    protected static int|string $part2Expected = 70189;

    protected int $safe = 0;
    protected int $fall = 0;

    public function initialize(): void
    {
        $bricks = $this->parse($this->getInput()->getArrayData());
        usort($bricks, fn ($a, $b) => min($a['start'][2], $a['end'][2]) <=> min($b['start'][2], $b['end'][2]));

        [$bricks, $moves] = $this->adjustBrickPositions($bricks);

        foreach ($bricks as $key => $brick) {
            $newBricks = $bricks;
            unset($newBricks[$key]);

            [, $moves] = $this->adjustBrickPositions($newBricks);

            if (0 === $moves) {
                ++$this->safe;
            } else {
                $this->fall += $moves;
            }

            dump(json_encode([$key, $this->safe, $this->fall]));
        }
    }

    private function parse(array $data): array
    {
        $bricks = [];
        foreach ($data as $entry) {
            [$start, $end] = explode('~', $entry);

            [$startX, $startY, $startZ] = explode(',', $start);
            [$endX, $endY, $endZ] = explode(',', $end);

            $bricks[] = [
                'start' => [(int) $startX, (int) $startY, (int) $startZ],
                'end' => [(int) $endX, (int) $endY,  (int) $endZ],
            ];
        }

        return $bricks;
    }

    public function part1(): int
    {
        return $this->safe;
    }

    public function part2(): int
    {
        return $this->fall;
    }

    private function adjustBrickPositions($bricks): array
    {
        $fall = true;
        $moves = 0;
        while ($fall) {
            $fall = false;

            foreach ($bricks as $key => $brick) {
                $adjusted = $this->adjustBrickBelow($bricks, $brick);
                if ($adjusted !== $brick) {
                    $bricks[$key] = $adjusted;
                    $fall = true;

                    ++$moves;
                }
            }
        }

        return [$bricks, $moves];
    }

    private function adjustBrickBelow($bricks, $currentBrick)
    {
        $z = 0;
        $minStartZ = min($currentBrick['start'][2], $currentBrick['end'][2]);
        foreach ($bricks as $otherBrick) {
            if ($otherBrick === $currentBrick) {
                continue;
            }

            $maxZ = max($otherBrick['start'][2], $otherBrick['end'][2]);
            $minZ = min($otherBrick['start'][2], $otherBrick['end'][2]);

            if ($minStartZ < $minZ) {
                continue;
            }

            if ($this->isBetween($currentBrick, $otherBrick)) {
                $z = max($z, $maxZ);
            }
        }

        $dZ = $minStartZ - $z - 1;

        $currentBrick['start'][2] -= $dZ;
        $currentBrick['end'][2] -= $dZ;

        return $currentBrick;
    }

    public function isBetween($brickA, $brickB): bool
    {
        $startA = $brickA['start'];
        $endA = $brickA['end'];
        $startB = $brickB['start'];
        $endB = $brickB['end'];

        $overlapX = max($startA[0], $endA[0]) >= min($startB[0], $endB[0]) && min($startA[0], $endA[0]) <= max($startB[0], $endB[0]);
        $overlapY = max($startA[1], $endA[1]) >= min($startB[1], $endB[1]) && min($startA[1], $endA[1]) <= max($startB[1], $endB[1]);

        return $overlapX && $overlapY;
    }
}
