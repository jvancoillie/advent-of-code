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

    public function part1(): string
    {
        [$stacks, $moves] = $this->getParsedData();

        return $this->applyMoves($stacks, $moves);
    }

    public function part2(): string
    {
        [$stacks, $moves] = $this->getParsedData();

        return $this->applyMoves($stacks, $moves, false);
    }

    private function getParsedData(): array
    {
        $data = explode("\n\n", $this->getInput()->getData(false));
        $entry = array_fill(1, 9, []);

        foreach (explode("\n", $data[0]) as $line) {
            $split = array_map('trim', str_split($line, 4));
            foreach ($split as $i => $value) {
                if (preg_match('/\[(.*)\]/', $value, $matches)) {
                    $entry[$i + 1][] = $matches[1];
                }
            }
        }

        return [array_map(fn ($e) => array_reverse($e), $entry), explode("\n", $data[1])];
    }

    protected function applyMoves(array $stacks, array $moves, bool $reversed = true): string
    {
        foreach ($moves as $l) {
            preg_match('/move (.*) from (.*) to (.*)/', $l, $m);
            $move = array_slice($stacks[(int) $m[2]], -$m[1]);
            if ($reversed) {
                $move = array_reverse($move);
            }
            $stacks[(int) $m[2]] = array_slice($stacks[(int) $m[2]], 0, count($stacks[(int) $m[2]]) - $m[1]);
            $stacks[(int) $m[3]] = array_merge($stacks[(int) $m[3]], $move);
        }

        return implode(array_map(fn ($e) => array_pop($e), $stacks));
    }
}
