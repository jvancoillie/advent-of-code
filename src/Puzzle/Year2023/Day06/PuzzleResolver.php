<?php

namespace App\Puzzle\Year2023\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 352;
    protected static int|string $testPart2Expected = 71503;

    protected static int|string $part1Expected = 1312850;
    protected static int|string $part2Expected = 36749103;

    protected array $data = [];

    public function initialize(): void
    {
        $data = $this->getInput()->getArrayData();
        foreach ($data as $i => $line) {
            [$type, $list] = explode(':', $line);
            $this->data[$type] = preg_split('/\s+/', trim($list));
        }
    }

    public function part1(): int
    {
        return $this->getWays($this->data['Time'], $this->data['Distance']);
    }

    public function part2(): int
    {
        return $this->getWays([join($this->data['Time'])], [join($this->data['Distance'])]);
    }

    public function getWays($times, $distances): int
    {
        $ways = 1;

        for ($i = 0; $i < count($times); ++$i) {
            $record = $distances[$i];
            $durationsToBeat = 0;

            for ($j = 1; $j <= $times[$i]; ++$j) {
                $distance = $j + (($times[$i] - $j) * ($j - 1));

                if ($distance >= $record) {
                    ++$durationsToBeat;
                }
            }

            $ways *= $durationsToBeat;
        }

        return $ways;
    }
}
