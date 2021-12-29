<?php

namespace App\Puzzle\Year2020\Day13;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 295;
    protected static int|string $testPart2Expected = 1068781;

    protected static int|string $part1Expected = 4782;
    protected static int|string $part2Expected = 1118684865113056;

    private int $timestamp;
    private array $busIds;

    protected function initialize(): void
    {
        $data = $this->getInput()->getArrayData();
        $this->timestamp = array_shift($data);
        $this->busIds = explode(',', array_shift($data));
    }

    public function part1(): int
    {
        $bestTime = INF;
        $bestId = 0;

        foreach ($this->busIds as $id) {
            if ('x' !== $id) {
                $next = $this->timestamp + ($id - $this->timestamp % $id);
                if ($next < $bestTime) {
                    $bestTime = $next;
                    $bestId = $id * ($next - $this->timestamp);
                }
            }
        }

        return $bestId;
    }

    public function part2(): int
    {
        $pairs = [];
        foreach ($this->busIds as $key => $id) {
            if ('x' !== $id) {
                $pairs[] = [$id - $key, $id];
            }
        }

        return $this->crt($pairs);
    }

    /**
     * @see https://en.wikipedia.org/wiki/Chinese_remainder_theorem
     */
    private function crt($pairs): int
    {
        $m = 1;
        foreach ($pairs as [$x, $mx]) {
            $m *= $mx;
        }

        $total = 0;

        foreach ($pairs as [$x, $mx]) {
            $b = $m / $mx;

            $a = gmp_strval(gmp_mul($x, $b));
            $pow = gmp_strval(gmp_powm($b, $mx - 2, $mx));
            $res = gmp_strval(gmp_mul($a, $pow));

            $total = gmp_strval(gmp_add($total, $res));
            $total = gmp_strval(gmp_mod($total, $m));
        }

        return (int) $total;
    }
}
