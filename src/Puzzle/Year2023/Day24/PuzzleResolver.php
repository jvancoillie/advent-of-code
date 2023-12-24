<?php

namespace App\Puzzle\Year2023\Day24;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/24
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 47;

    protected static int|string $part1Expected = 31208;
    protected static int|string $part2Expected = 580043851566574;

    public function part1(): int
    {
        $ans = 0;
        $hailstones = $this->parse($this->getInput()->getArrayData());

        $area = $this->isTestMode() ? [7, 27] : [200000000000000, 400000000000000];

        for ($i = 0; $i < count($hailstones); ++$i) {
            for ($j = $i + 1; $j < count($hailstones); ++$j) {
                if ($this->intersectInArea($hailstones[$i], $hailstones[$j], $area)) {
                    ++$ans;
                }
            }
        }

        return $ans;
    }

    // not found :(
    public function part2(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        return $ans;
    }

    public function intersectInArea(array $pointA, array $pointB, array $area): bool
    {
        [$ax, $ay] = $pointA['point'];
        [$avx, $avy] = $pointA['velocity'];

        [$bx, $by] = $pointB['point'];
        [$bvx, $bvy] = $pointB['velocity'];

        $denominator = bcsub(
            bcmul(bcsub($ax, bcadd($ax, $avx)), bcsub($by, bcadd($by, $bvy))),
            bcmul(bcsub($ay, bcadd($ay, $avy)), bcsub($bx, bcadd($bx, $bvx)))
        );

        if (0 === (int) $denominator) {
            return false;
        }

        $px = bcdiv(
            bcsub(
                bcmul(
                    bcsub(
                        bcmul($ax, bcadd($ay, $avy)),
                        bcmul($ay, bcadd($ax, $avx))
                    ),
                    bcsub($bx, bcadd($bx, $bvx))
                ),
                bcmul(
                    bcsub($ax, bcadd($ax, $avx)),
                    bcsub(
                        bcmul($bx, bcadd($by, $bvy)),
                        bcmul($by, bcadd($bx, $bvx))
                    )
                )
            ),
            $denominator
        );

        $py = bcdiv(
            bcsub(
                bcmul(
                    bcsub(
                        bcmul($ax, bcadd($ay, $avy)),
                        bcmul($ay, bcadd($ax, $avx))
                    ),
                    bcsub($by, bcadd($by, $bvy))
                ),
                bcmul(
                    bcsub($ay, bcadd($ay, $avy)),
                    bcsub(
                        bcmul($bx, bcadd($by, $bvy)),
                        bcmul($by, bcadd($bx, $bvx))
                    )
                )
            ),
            $denominator
        );

        if (
            $px > $ax === bcadd($ax, $avx) > $ax
            && $px > $bx === bcadd($bx, $bvx) > $bx
            && $area[0] <= $px && $px <= $area[1]
            && $area[0] <= $py && $py <= $area[1]
        ) {
            return true;
        }

        return false;
    }

    public function parse(array $data): array
    {
        $hailstones = [];

        foreach ($data as $line) {
            [$point, $velocity] = explode('@', $line);

            $hailstones[] = [
                'point' => array_map('intval', explode(', ', $point)),
                'velocity' => array_map('intval', explode(', ', $velocity)),
            ];
        }

        return $hailstones;
    }
}
