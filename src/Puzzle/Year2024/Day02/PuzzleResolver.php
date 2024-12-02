<?php

namespace App\Puzzle\Year2024\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 4;

    protected static int|string $part1Expected = 516;
    protected static int|string $part2Expected = 561;

    public function part1()
    {
        $ans = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $entry) {
            $list = preg_split('@\s+@', $entry);

            if ('safe' === $this->analyze($list)) {
                ++$ans;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        $data = $this->getInput()->getArrayData();

        foreach ($data as $entry) {
            $list = preg_split('@\s+@', $entry);

            if ('safe' === $this->analyze($list)) {
                ++$ans;
                continue;
            }

            $isSafe = false;
            foreach ($list as $index => $value) {
                $testList = $list;
                unset($testList[$index]);
                $testList = array_values($testList);

                if ('safe' === $this->analyze($testList)) {
                    $isSafe = true;
                    break;
                }
            }

            if ($isSafe) {
                ++$ans;
            }
        }

        return $ans;
    }

    private function analyze(array $list): int|string
    {
        $type = ($list[0] > $list[1]) ? 'dec' : 'inc';

        foreach ($list as $i => $current) {
            if (!isset($list[$i + 1])) {
                break;
            }

            $next = $list[$i + 1];
            $diff = $current - $next;

            if (abs($diff) > 3 || 0 === $diff || $type !== (($diff > 0) ? 'dec' : 'inc')) {
                return $i;
            }
        }

        return 'safe';
    }
}
