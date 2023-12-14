<?php

namespace App\Puzzle\Year2023\Day14;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 136;
    protected static int|string $testPart2Expected = 64;

    protected static int|string $part1Expected = 108792;
    protected static int|string $part2Expected = 99118;

    public function part1(): int
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());

        return $this->totalLoad($this->moveRocks($data));
    }

    public function part2(): int
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());
        $directions = ['N', 'W', 'S', 'E'];
        $memo = [];
        $rounds = 1000000000;

        for ($i = 1; $i <= $rounds; ++$i) {
            foreach ($directions as $d) {
                $data = $this->moveRocks($data);
                $data = Grid::rotate($data);
            }

            $key = $this->getKey($data);

            $i += isset($memo[$key]) ? intdiv($rounds - $i, $i - $memo[$key]) * ($i - $memo[$key]) : 0;

            $memo[$key] = $i;
        }

        return $this->totalLoad($data);
    }

    public function moveRocks(array $data): array
    {
        foreach ($data as $x => $row) {
            foreach ($row as $y => $cell) {
                if ('O' === $cell) {
                    $data[$x][$y] = '.';
                    $nx = $x;
                    while ($nx > 0) {
                        --$nx;
                        if (!isset($data[$nx][$y]) || '.' !== $data[$nx][$y]) {
                            ++$nx;
                            break;
                        }
                    }
                    $data[$nx][$y] = 'O';
                }
            }
        }

        return $data;
    }

    public function totalLoad(array $data): int
    {
        $ans = 0;
        foreach ($data as $x => $row) {
            foreach ($row as $y => $cell) {
                if ('O' === $cell) {
                    $ans += count($row) - $x;
                }
            }
        }

        return $ans;
    }

    private function getKey(array $data): string
    {
        return join(array_map('join', $data));
    }
}
