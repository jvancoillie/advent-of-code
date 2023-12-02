<?php

namespace App\Puzzle\Year2023\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 8;
    protected static int|string $testPart2Expected = 2286;

    protected static int|string $part1Expected = 2486;
    protected static int|string $part2Expected = 87984;

    private array $game = [];

    public function initialize(): void
    {
        $this->game = $this->parse($this->getInput()->getArrayData());
    }

    public function part1(): int|string
    {
        $ans = 0;

        $max = ['red' => 12, 'green' => 13, 'blue' => 14];

        foreach ($this->game as $id => $game) {
            $possible = true;
            foreach ($game as $cubes) {
                foreach ($cubes as $cube => $count) {
                    if ($max[$cube] < $count) {
                        $possible = false;
                        break 2;
                    }
                }
            }

            if ($possible) {
                $ans += $id;
            }
        }

        return $ans;
    }

    public function part2(): float|int
    {
        $ans = 0;

        foreach ($this->game as $bag) {
            $r = ['red' => 0, 'blue' => 0, 'green' => 0];
            foreach ($bag as $cubes) {
                foreach ($cubes as $c => $n) {
                    if ($r[$c] < $n) {
                        $r[$c] = $n;
                    }
                }
            }

            $ans += array_product($r);
        }

        return $ans;
    }

    private function parse(array $data): array
    {
        $game = [];

        foreach ($data as $line) {
            $round = explode(':', $line);

            if (preg_match('/^Game\s(?P<gameId>\d+)$/', $round[0], $matches)) {
                $game[(int) $matches['gameId']] = array_map(function ($entry) {
                    $c = [];
                    foreach (explode(',', $entry) as $cubes) {
                        preg_match('/^(?P<n>\d+)\s(?P<cube>.*)/', trim($cubes), $m);
                        $c[$m['cube']] = (int) $m['n'];
                    }

                    return $c;
                }, explode(';', $round[1]));
            }
        }

        return $game;
    }
}
