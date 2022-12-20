<?php

namespace App\Puzzle\Year2022\Day20;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/20
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3;
    protected static int|string $testPart2Expected = 1623178306;

    protected static int|string $part1Expected = 8028;
    protected static int|string $part2Expected = 8798438007673;

    public const GROVE = [1000, 2000, 3000];

    public function part1(): int
    {
        $original = array_map('intval', $this->getInput()->getArrayData());

        $mapped = $this->mapOriginal($original);

        $mapped = $this->move($original, $mapped);

        return $this->getGrove($mapped);
    }

    public function part2(): int
    {
        $original = array_map(fn ($e) => $e * 811589153, $this->getInput()->getArrayData());
        $mapped = $this->mapOriginal($original);

        foreach (range(1, 10) as $loop) {
            $mapped = $this->move($original, $mapped);
        }

        return $this->getGrove($mapped);
    }

    private function move(array $data, array $copy): array
    {
        $size = count($data) - 1;

        for ($i = 0; $i < count($data); ++$i) {
            $number = $data[$i];
            $value = "$i|$number";
            $key = array_search($value, $copy);
            $from = $key;
            $to = ($from + $number) % $size;

            if ($to < 0) {
                $to = $size + $to;
            } elseif (0 === $to) {
                $to = $size;
            }

            unset($copy[$from]);

            $copy = array_values($copy);
            $right = array_slice($copy, $to);
            $left = array_slice($copy, 0, $to);
            $copy = array_merge($left, [$value], $right);
        }

        return $copy;
    }

    private function getGrove(array $array): int
    {
        $array = array_map(fn ($e) => (int) explode('|', $e)[1], $array);

        $ans = 0;
        $indexZero = array_search(0, $array);
        foreach (self::GROVE as $pos) {
            $i = ($pos + $indexZero) % count($array);
            $ans += $array[$i];
        }

        return $ans;
    }

    private function mapOriginal(array $original): array
    {
        return array_map(fn ($key, $value) => "$key|$value", array_keys($original), $original);
    }
}
