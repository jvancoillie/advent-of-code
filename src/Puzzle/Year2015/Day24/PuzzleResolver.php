<?php

namespace App\Puzzle\Year2015\Day24;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/24
 *
 * Not found @see https://www.reddit.com/r/adventofcode/comments/3y1s7f/day_24_solutions/
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 88;
    protected static int|string $testPart2Expected = 33;

    protected static int|string $part1Expected = 10723906903;
    protected static int|string $part2Expected = 74850409;

    public function part1()
    {
        $packages = explode("\n", $this->getInput()->getData());
        $list = $this->createGroups(array_sum($packages) / 3, $packages);
        $min = INF;
        foreach ($list as $item) {
            $product = array_product($item);
            if ($product < $min) {
                $min = $product;
            }
        }

        return $min;
    }

    public function part2()
    {
        $packages = explode("\n", $this->getInput()->getData());
        $list = $this->createGroups(array_sum($packages) / 4, $packages);
        $min = INF;
        foreach ($list as $item) {
            $product = array_product($item);
            if ($product < $min) {
                $min = $product;
            }
        }

        return $min;
    }

    private function createGroups(int|float $weight, array $packages = [], $group = [], $checked = []): array
    {
        $list = [];

        while ($packages) {
            $package = array_pop($packages);
            $weightCheck = array_sum($group) + $package;
            if ($weight < $weightCheck) {
                $checked[] = $package;
                continue;
            }
            $newGroup = $group;
            $newGroup[] = $package;
            if ($weightCheck === $weight && $this->isValid(array_merge($checked, $packages), $weight)) {
                $list[] = $newGroup;
            }

            $list = array_merge($list, $this->createGroups($weight, $packages, $newGroup, $checked));
        }

        return array_unique($list, SORT_REGULAR);
    }

    private function isValid(array $group, $targetWeight, $weight = 0): bool
    {
        while ($group) {
            $item = array_pop($group);
            $newWeight = $weight + $item;
            if ($newWeight === $targetWeight) {
                return true;
            }
            if ($newWeight < $targetWeight && $this->isValid($group, $targetWeight, $newWeight)) {
                return true;
            }
        }

        return false;
    }
}
