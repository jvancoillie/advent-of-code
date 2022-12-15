<?php

namespace App\Puzzle\Year2022\Day15;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 26;
    protected static int|string $testPart2Expected = 56000011;

    protected static int|string $part1Expected = 4582667;
    protected static int|string $part2Expected = 10961118625406;

    public function part1(): int
    {
        $lineToCheck = $this->isTestMode() ? 10 : 2000000;
        $grid = [$lineToCheck => []];
        $sensors = $this->parse();

        foreach ($sensors as [$sensor, $beacon, $dist]) {
            if (abs($lineToCheck - $sensor[1]) <= $dist) {
                $y = abs($lineToCheck - $sensor[1]);

                for ($dx = -$dist; $dx <= $dist; ++$dx) {
                    $x = $sensor[0] + $dx;
                    if ((abs($x - $sensor[0]) + $y) <= $dist) {
                        $grid[$lineToCheck][$x] = '#';
                    }
                }
            }
            $grid[$sensor[1]][$sensor[0]] = 'S';
            $grid[$beacon[1]][$beacon[0]] = 'B';
        }

        return substr_count(implode('', $grid[$lineToCheck]), '#');
    }

    public function part2(): int
    {
        $area = $this->isTestMode() ? 20 : 4000000;
        $sensors = $this->parse();

        foreach ($sensors as [$sensor, $beacon, $dist]) {
            for ($dx = -($dist + 1); $dx <= ($dist + 1); ++$dx) {
                $diff = abs($dx - ($dist + 1));

                $x = $sensor[0] + $dx;
                $yr = $sensor[1] - $diff;
                $yl = $sensor[1] + $diff;

                if ($x <= $area && $x >= 0 && $yl <= $area && $yl >= 0 && $this->check($x, $yl, $sensors)) {
                    return $x * 4000000 + $yl;
                }
                if ($x <= $area && $x >= 0 && $yr <= $area && $yr >= 0 && $this->check($x, $yr, $sensors)) {
                    return $x * 4000000 + $yr;
                }
            }
        }

        return 0;
    }

    private function check(mixed $x, mixed $y, array $sensors): bool
    {
        foreach ($sensors as [$sensor, $beacon, $dist]) {
            if ((abs($x - $sensor[0]) + abs($y - $sensor[1])) <= $dist) {
                return false;
            }
        }

        return true;
    }

    private function parse(): array
    {
        $sensors = [];
        $data = $this->getInput()->getArrayData();
        foreach ($data as $entry) {
            preg_match('/Sensor at x=(?P<sensorX>.*), y=(?P<sensorY>.*): closest beacon is at x=(?P<beaconX>.*), y=(?P<beaconY>.*)/', $entry, $m);

            $sensor = [(int) $m['sensorX'], (int) $m['sensorY']];
            $beacon = [(int) $m['beaconX'], (int) $m['beaconY']];
            $dist = abs($sensor[0] - $beacon[0]) + abs($sensor[1] - $beacon[1]);
            $sensors[] = [$sensor, $beacon, $dist];
        }

        return $sensors;
    }
}
