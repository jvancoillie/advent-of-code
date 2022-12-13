<?php

namespace App\Puzzle\Year2022\Day13;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 13;
    protected static int|string $testPart2Expected = 140;

    protected static int|string $part1Expected = 5843;
    protected static int|string $part2Expected = 26289;

    public function part1(): int
    {
        $ans = 0;

        $data = explode("\n\n", $this->getInput()->getData());

        foreach ($data as $num => $pair) {
            [$a, $b] = explode("\n", $pair);
            if (-1 === $this->compare(json_decode($a), json_decode($b))) {
                $ans += ($num + 1);
            }
        }

        return $ans;
    }

    public function part2(): int
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $packets = [];
        foreach ($data as $pair) {
            [$a, $b] = explode("\n", $pair);
            $packets[] = json_decode($a);
            $packets[] = json_decode($b);
        }

        $a = '[[2]]';
        $b = '[[6]]';
        $packets[] = json_decode('[[2]]');
        $packets[] = json_decode('[[6]]');

        usort($packets, [$this, 'compare']);
        $stringify = array_map(function ($e) { return json_encode($e); }, $packets);

        return (array_search($a, $stringify) + 1) * (array_search($b, $stringify) + 1);
    }

    private function compare(mixed $left, mixed $right): int
    {
        if (is_int($left) && is_int($right)) {
            return $left <=> $right;
        }

        if (is_array($left) && is_array($right)) {
            for ($i = 0; $i < count($left); ++$i) {
                if (!isset($right[$i])) {
                    return 1;
                }

                $compare = $this->compare($left[$i], $right[$i]);

                if (0 === $compare) {
                    continue;
                }

                return $compare;
            }

            if (isset($right[$i])) {
                return -1;
            }

            return 0;
        }

        if (is_array($left)) {
            return $this->compare($left, [$right]);
        }

        if (is_array($right)) {
            return $this->compare([$left], $right);
        }

        return 0;
    }
}
