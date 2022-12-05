<?php

namespace App\Puzzle\Year2022\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 'CMZ';
    protected static int|string $testPart2Expected = 'MCD';

    protected static int|string $part1Expected = 'SBPQRSCDF';
    protected static int|string $part2Expected = 'RGLVRCQSB';

    public function part1()
    {
        [$entry, $moves] = $this->getParsedData();

        foreach ($moves as $l) {
            preg_match('/move (.*) from (.*) to (.*)/', $l, $m);
            $move = array_slice($entry[(int) $m[2]], -$m[1]);
            $entry[(int) $m[2]] = array_slice($entry[(int) $m[2]], 0, count($entry[(int) $m[2]]) - $m[1]);
            $entry[(int) $m[3]] = array_merge($entry[(int) $m[3]], array_reverse($move));
        }

        return implode(array_map(fn ($e) => array_pop($e), $entry));
    }

    public function part2()
    {
        [$entry, $moves] = $this->getParsedData();

        foreach ($moves as $l) {
            preg_match('/move (.*) from (.*) to (.*)/', $l, $m);
            $move = array_slice($entry[(int) $m[2]], -$m[1]);
            $entry[(int) $m[2]] = array_slice($entry[(int) $m[2]], 0, count($entry[(int) $m[2]]) - $m[1]);
            $entry[(int) $m[3]] = array_merge($entry[(int) $m[3]], $move);
        }

        return implode(array_map(fn ($e) => array_pop($e), $entry));
    }

    private function getParsedData(): array
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $entry = [];
        foreach (explode("\n", $data[0]) as $line) {
            $split = array_map('trim', str_split($line, 4));
            foreach ($split as $i => $value) {
                if (preg_match('/\[(.*)\]/', $value, $matches)) {
                    if (!isset($entry[$i + 1])) {
                        $entry[$i + 1] = [];
                    }
                    array_unshift($entry[$i + 1], $matches[1]);
                }
            }
        }

        ksort($entry);

        return [$entry, explode("\n", $data[1])];
    }
}
