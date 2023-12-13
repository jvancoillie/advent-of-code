<?php

namespace App\Puzzle\Year2023\Day12;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 21;
    protected static int|string $testPart2Expected = 525152;

    protected static int|string $part1Expected = 6488;
    protected static int|string $part2Expected = 815364548481;

    protected array $memoization = [];

    public function part1(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $row) {
            $this->memoization = [];
            [$knownSprings, $groups] = explode(' ', $row);
            $ans += $this->countArrangements($knownSprings, $groups, 0, substr_count($knownSprings, '?'));
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();
        foreach ($data as $i => $row) {
            $this->memoization = [];
            [$knownSprings, $groups] = explode(' ', $row);
            [$knownSprings, $groups] = $this->repeat($knownSprings, $groups);
            dump("$i => $row");
            $ans += $this->countArrangements($knownSprings, $groups, 0, substr_count($knownSprings, '?'));
        }

        return $ans;
    }

    /** TODO improve this memory size :( :( :( */
    public function countArrangements($knownSprings, $groups, $pos = 0, $remaining = 0): int
    {
        //        $key = "$pos$knownSprings$remaining";
        //
        //        if (isset($this->memoization[$key])) {
        //            return $this->memoization[$key];
        //        }

        if (0 === $remaining) {
            return $this->isValid($knownSprings, $groups, $remaining) ? 1 : 0;
        }

        if (!$this->isValid($knownSprings, $groups, $remaining)) {
            return 0;
        }

        if ('?' === $knownSprings[$pos]) {
            $a = $knownSprings;
            $b = $knownSprings;

            $a[$pos] = '#';
            $b[$pos] = '.';

            return $this->countArrangements($a, $groups, $pos + 1, $remaining - 1) + $this->countArrangements($b, $groups, $pos + 1, $remaining - 1);
        }

        return $this->countArrangements($knownSprings, $groups, $pos + 1, $remaining);

        //        return $this->memoization[$key] = $sum;
    }

    private function repeat(string $knownSprings, string $groups): array
    {
        return [substr(str_repeat($knownSprings.'?', 5), 0, -1), substr(str_repeat($groups.',', 5), 0, -1)];
    }

    private function isValid($knownSprings, $groups, $remaining): bool
    {
        $groups = array_map('intval', explode(',', $groups));

        if (0 === $remaining) {
            $r = array_values(array_map('strlen', array_filter(explode('.', $knownSprings))));

            return $r === $groups;
        }

        $pos = strpos($knownSprings, '?');

        if (false !== $pos) {
            $r = array_values(array_map(fn ($e) => strlen($e), array_filter(explode('.', substr($knownSprings, 0, $pos)))));

            if (!$r) {
                return true;
            }

            $count = count($r);

            if ($count > count($groups)) {
                return false;
            }

            foreach ($r as $i => $value) {
                if ($i === $count - 1) {
                    return $groups[$i] >= $value;
                }

                if ($groups[$i] !== $value) {
                    return false;
                }
            }
        }

        throw new \Exception('unreachable');
    }
}
