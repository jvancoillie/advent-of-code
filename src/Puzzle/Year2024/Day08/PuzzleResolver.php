<?php

namespace App\Puzzle\Year2024\Day08;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public const DOT = '.';
    protected static int|string $testPart1Expected = 14;
    protected static int|string $testPart2Expected = 34;

    protected static int|string $part1Expected = 271;
    protected static int|string $part2Expected = 994;

    public function part1(): int
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());
        $antennas = $this->antennas($data);
        $antiNodes = $this->antiNodes($antennas, $data);

        return count($antiNodes);
    }

    public function part2(): int
    {
        $data = array_map('str_split', $this->getInput()->getArrayData());
        $antennas = $this->antennas($data);
        $antiNodes = $this->antiNodes($antennas, $data, true);

        return count($antiNodes);
    }

    private function antiNodes(array $antennas, array $grid, bool $repeat = false): array
    {
        $antiNodes = [];
        foreach ($antennas as $positions) {
            for ($i = 0; $i < count($positions); ++$i) {
                if ($repeat && !in_array($positions[$i], $antiNodes)) {
                    $antiNodes[] = $positions[$i];
                }

                for ($j = 0; $j < count($positions); ++$j) {
                    if ($i === $j) {
                        continue;
                    }

                    $dist = [$positions[$j][0] - $positions[$i][0], $positions[$j][1] - $positions[$i][1]];
                    $antiNode = [$positions[$i][0] - $dist[0], $positions[$i][1] - $dist[1]];

                    while (isset($grid[$antiNode[0]][$antiNode[1]])) {
                        if (!in_array($antiNode, $antiNodes)) {
                            $antiNodes[] = $antiNode;
                        }

                        $grid[$antiNode[0]][$antiNode[1]] = '#';

                        if (!$repeat) {
                            break;
                        }

                        $antiNode = [$antiNode[0] - $dist[0], $antiNode[1] - $dist[1]];
                    }
                }
            }
        }

        return $antiNodes;
    }

    private function antennas(array $data): array
    {
        $antennas = [];
        foreach ($data as $y => $row) {
            foreach ($row as $x => $value) {
                if (self::DOT !== $value) {
                    $antennas[$value][] = [$y, $x];
                }
            }
        }

        return $antennas;
    }
}
