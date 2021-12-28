<?php

namespace App\Puzzle\Year2020\Day11;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 37;
    protected static int|string $testPart2Expected = 26;

    protected static int|string $part1Expected = 2483;
    protected static int|string $part2Expected = 2285;

    private array $ferry;

    protected function initialize(): void
    {
        $this->ferry = array_map('str_split', $this->getInput()->getArrayData());
    }

    public function part1(): int
    {
        return $this->seatsOccupied($this->ferry);
    }

    public function part2(): int
    {
        return $this->seatsOccupied($this->ferry, true, 5);
    }

    private function seatsOccupied($ferry, $forward = false, $numberOfPlacesOccupied = 4): int
    {
        while (true) {
            $round = $ferry;
            for ($x = 0; $x < count($ferry); ++$x) {
                for ($y = 0; $y < count($ferry[$x]); ++$y) {
                    $type = $ferry[$x][$y];
                    if ('.' !== $type && $this->needChange($x, $y, $ferry, $forward, $numberOfPlacesOccupied)) {
                        $round[$x][$y] = '#' === $type ? 'L' : '#';
                    }
                }
            }

            if ($ferry == $round) {
                return $this->countOccupied($ferry);
            }

            $ferry = $round;
        }
    }

    private function countOccupied($array): int
    {
        $total = 0;
        foreach ($array as $lines) {
            $counts = array_count_values($lines);
            $total += $counts['#'] ?? 0;
        }

        return $total;
    }

    private function needChange($x, $y, $ferry, $forwarded = false, $numberOfPlacesOccupied = 4): bool
    {
        $directions = [
            [-1, -1],
            [-1, 0],
            [-1, 1],
            [0, -1],
            [0, 1],
            [1, -1],
            [1, 0],
            [1, 1],
        ];
        $type = $ferry[$x][$y];
        $count = 0;
        /**
         * @var int $dx
         * @var int $dy
         */
        foreach ($directions as [$dx, $dy]) {
            $tryNext = $forwarded;
            $i = 1;
            do {
                $nx = $x + $dx * $i;
                $ny = $y + $dy * $i;
                if (isset($ferry[$nx][$ny])) {
                    if ('.' === $ferry[$nx][$ny]) {
                        ++$i;
                    } else {
                        if ('#' === $type && $ferry[$nx][$ny] === $type) {
                            ++$count;
                        } else {
                            if ('L' === $type) {
                                if ($ferry[$nx][$ny] !== $type) {
                                    return false;
                                }
                            }
                        }
                        $tryNext = false;
                    }
                } else {
                    $tryNext = false;
                }
            } while ($tryNext);
        }

        if ('#' === $type && $count < $numberOfPlacesOccupied) {
            return false;
        }

        return true;
    }
}
