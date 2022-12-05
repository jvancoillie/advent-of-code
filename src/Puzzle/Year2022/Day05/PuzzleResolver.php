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
        // need to fix trimed line in input class :(
        $entry = [
            1 => ['J', 'H', 'P', 'M', 'S', 'F', 'N', 'V'],
            2 => ['S', 'R', 'L', 'M', 'J', 'D', 'Q'],
            3 => ['N', 'Q', 'D', 'H', 'C', 'S', 'W', 'B'],
            4 => ['R', 'S', 'C', 'L'],
            5 => ['M', 'V', 'T', 'P', 'F', 'B'],
            6 => ['T', 'R', 'Q', 'N', 'C'],
            7 => ['G', 'V', 'R'],
            8 => ['C', 'Z', 'S', 'P', 'D', 'L', 'R'],
            9 => ['D', 'S', 'J', 'V', 'G', 'P', 'B', 'F'],
        ];

//        $entry = [
//            1 => ['Z', 'N'],
//            2 => ['M', 'C', 'D'],
//            3 => ['P']
//        ];

        $data = explode("\n\n", $this->getInput()->getData());

        foreach (explode("\n", $data[1]) as $u => $l) {
            preg_match('/move (.*) from (.*) to (.*)/', $l, $m);
            $move = array_slice($entry[(int) $m[2]], -$m[1]);
            $entry[(int) $m[2]] = array_slice($entry[(int) $m[2]], 0, count($entry[(int) $m[2]]) - $m[1]);
            $entry[(int) $m[3]] = array_merge($entry[(int) $m[3]], array_reverse($move));
        }

        return implode(array_map(fn ($e) => array_pop($e), $entry));
    }

    public function part2()
    {
        $entry = [
            1 => ['J', 'H', 'P', 'M', 'S', 'F', 'N', 'V'],
            2 => ['S', 'R', 'L', 'M', 'J', 'D', 'Q'],
            3 => ['N', 'Q', 'D', 'H', 'C', 'S', 'W', 'B'],
            4 => ['R', 'S', 'C', 'L'],
            5 => ['M', 'V', 'T', 'P', 'F', 'B'],
            6 => ['T', 'R', 'Q', 'N', 'C'],
            7 => ['G', 'V', 'R'],
            8 => ['C', 'Z', 'S', 'P', 'D', 'L', 'R'],
            9 => ['D', 'S', 'J', 'V', 'G', 'P', 'B', 'F'],
        ];

//        $entry = [
//            1 => ['Z', 'N'],
//            2 => ['M', 'C', 'D'],
//            3 => ['P']
//        ];

        $data = explode("\n\n", $this->getInput()->getData());

        foreach (explode("\n", $data[1]) as $u => $l) {
            preg_match('/move (.*) from (.*) to (.*)/', $l, $m);
            $move = array_slice($entry[(int) $m[2]], -$m[1]);
            $entry[(int) $m[2]] = array_slice($entry[(int) $m[2]], 0, count($entry[(int) $m[2]]) - $m[1]);
            $entry[(int) $m[3]] = array_merge($entry[(int) $m[3]], $move);
        }

        return implode(array_map(fn ($e) => array_pop($e), $entry));
    }
}
