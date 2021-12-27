<?php

namespace App\Puzzle\Year2021\Day24;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/24
 *
 * Tymscar reddit solution : https://www.reddit.com/r/adventofcode/comments/rnejv5/comment/hptfiwl/?utm_source=share&utm_medium=web2x&context=3
 * https://github.com/tymscar/Advent-Of-Code/tree/master/2021/javascript/day24
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 49917929934999;
    protected static int|string $part2Expected = 11911316711816;

    private array $parameters;

    public function initialize(): void
    {
        $instructions = [];

        foreach ($this->getInput()->getArrayData() as $line) {
            $instructions[] = explode(' ', $line);
        }

        $this->parameters = [];

        for ($index = 0; $index < 18 * 14; $index += 18) {
            $p1 = (int) $instructions[$index + 4][2];
            $p2 = (int) $instructions[$index + 5][2];
            $p3 = (int) $instructions[$index + 15][2];
            $this->parameters[] = [$p1, $p2, $p3];
        }
    }

    public function part1()
    {
        if ($this->isTestMode()) {
            return 0;
        }

        return $this->solve(function ($newMinMaxZ, $newZ, $minMaxZ, $z, $inputDigit) {
            return max($newMinMaxZ[$newZ], $minMaxZ[$z] * 10 + $inputDigit);
        });
    }

    public function part2()
    {
        if ($this->isTestMode()) {
            return 0;
        }

        return $this->solve(function ($newMinMaxZ, $newZ, $minMaxZ, $z, $inputDigit) {
            return min($newMinMaxZ[$newZ], $minMaxZ[$z] * 10 + $inputDigit);
        });
    }

    public function solve(callable $callable)
    {
        $minMaxZ = [0];

        foreach ($this->parameters as $param) {
            $newMinMaxZ = [];
            foreach (array_keys($minMaxZ) as $z) {
                for ($inputDigit = 1; $inputDigit <= 9; ++$inputDigit) {
                    $newZ = $this->theFunctionThatRepeats($param, $z, $inputDigit);
                    if (1 === $param[0] || (26 === $param[0] && $newZ < $z)) {
                        if (!isset($newMinMaxZ[$newZ])) {
                            $newMinMaxZ[$newZ] = $minMaxZ[$z] * 10 + $inputDigit;
                        } else {
                            $newMinMaxZ[$newZ] = $callable($newMinMaxZ, $newZ, $minMaxZ, $z, $inputDigit);
                        }
                    }
                }
            }
            $minMaxZ = $newMinMaxZ;
        }

        return $minMaxZ[0];
    }

    public function theFunctionThatRepeats($params, $z, $w): float|int
    {
        if (($z % 26 + $params[1]) !== $w) {
            return floor($z / $params[0]) * 26 + $w + $params[2];
        }

        return floor($z / $params[0]);
    }
}
