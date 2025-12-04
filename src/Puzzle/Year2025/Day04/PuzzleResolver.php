<?php

namespace App\Puzzle\Year2025\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2025/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 13;
    protected static int|string $testPart2Expected = 43;

    protected static int|string $part1Expected = 1370;
    protected static int|string $part2Expected = 8437;

    private const PAPER = '@';

    public function part1(): int
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());

        [, $count] = $this->accessPaper($data);

        return $count;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = array_map('str_split', $this->getInput()->getArrayData());

        do {
            [$data, $count] = $this->accessPaper($data);
            $ans += $count;
        } while ($count > 0);

        return $ans;
    }

    private function accessPaper(array $data): array
    {
        $count = 0;
        $new = $data;
        foreach ($data as $y => $line) {
            foreach ($line as $x => $cell) {
                if ('@' !== $cell) {
                    continue;
                }

                $neighbors = 0;
                foreach (Grid::$fullDirections as [$dx, $dy]) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;

                    if (isset($data[$ny][$nx]) && self::PAPER === $data[$ny][$nx]) {
                        if (++$neighbors >= 4) {
                            break;
                        }
                    }
                }

                if ($neighbors < 4) {
                    ++$count;
                    $new[$y][$x] = '.';
                }
            }
        }

        return [$new, $count];
    }
}
