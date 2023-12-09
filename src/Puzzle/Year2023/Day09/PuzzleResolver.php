<?php

namespace App\Puzzle\Year2023\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 114;
    protected static int|string $testPart2Expected = 2;

    protected static int|string $part1Expected = 1938800261;
    protected static int|string $part2Expected = 1112;

    public function part1(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $sequence) {
            $value = $this->getValue($sequence);
            $ans += $value;
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        foreach ($data as $sequence) {
            $value = $this->getValue($sequence, true);
            $ans += $value;
        }

        return $ans;
    }

    private function getValue(string $sequence, bool $getPrev = false): int
    {
        $seq = array_map('intval', explode(' ', $sequence));
        $list = [$seq[$getPrev ? 0 : array_key_last($seq)]];

        while (1 !== count(array_unique($seq))) {
            $nextSeq = [];

            for ($i = 0; $i < count($seq) - 1; ++$i) {
                $nextSeq[] = $seq[$i + 1] - $seq[$i];
            }

            $list[] = $getPrev ? $nextSeq[0] : end($nextSeq);
            $seq = $nextSeq;
        }

        if ($getPrev) {
            $list = array_reverse($list);
            $s = $list[1] - $list[0];

            for ($i = 2; $i < count($list); ++$i) {
                $s = $list[$i] - $s;
            }

            return $s;
        }

        return (int) array_sum($list);
    }
}
