<?php

namespace App\Puzzle\Year2021\Day23;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/23
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 12521;
    protected static int|string $testPart2Expected = 44169;

    protected static int|string $part1Expected = 14467;
    protected static int|string $part2Expected = 48759;

    public function part1(): int
    {
        if ($this->isTestMode()) {
            $rooms = [
                2 => ['B', 'A'],
                4 => ['C', 'D'],
                6 => ['B', 'C'],
                8 => ['D', 'A'],
            ];
        } else {
            $rooms = [
                2 => ['D', 'C'],
                4 => ['A', 'C'],
                6 => ['A', 'B'],
                8 => ['D', 'B'],
            ];
        }

        $submarine = new Submarine($rooms);

        return $this->search($submarine);
    }

    public function part2(): int
    {
        if ($this->isTestMode()) {
            $rooms = [
                2 => ['B', 'D', 'D', 'A'],
                4 => ['C', 'C', 'B', 'D'],
                6 => ['B', 'B', 'A', 'C'],
                8 => ['D', 'A', 'C', 'A'],
            ];
        } else {
            $rooms = [
                2 => ['D', 'D', 'D', 'C'],
                4 => ['A', 'C', 'B', 'C'],
                6 => ['A', 'B', 'A', 'B'],
                8 => ['D', 'A', 'C', 'B'],
            ];
        }

        $submarine = new Submarine($rooms);

        return $this->search($submarine);
    }

    public function search(Submarine $submarine): int
    {
        $queue = new \SplQueue();
        $queue->enqueue($submarine);
        $bestCost = PHP_INT_MAX;

        $memo = [];

        while (!$queue->isEmpty()) {
            /** @var Submarine $current */
            $current = $queue->pop();
            $currentStateHash = $current->getStateHash();

            if (isset($memo[$currentStateHash])) {
                if ($memo[$currentStateHash] < $current->getCost()) {
                    continue;
                }
                $memo[$currentStateHash] = $current->getCost();
            } else {
                $memo[$currentStateHash] = $current->getCost();
            }
            if ($current->isEnd()) {
                if ($current->getCost() < $bestCost) {
                    $bestCost = $current->getCost();
                }
                continue;
            }

            foreach ($current->getNextMoves() as $move) {
                $newState = clone $current;
                $newState->applyMove($move);
                $queue->enqueue($newState);
            }
        }

        return $bestCost;
    }
}
