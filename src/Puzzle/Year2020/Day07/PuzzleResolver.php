<?php

namespace App\Puzzle\Year2020\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4;
    protected static int|string $testPart2Expected = 32;

    protected static int|string $part1Expected = 278;
    protected static int|string $part2Expected = 45157;

    private string $goldBag = 'shiny gold';
    private array $inputs = [];
    private array $graph = [];

    protected function initialize(): void
    {
        foreach ($this->getInput()->getArrayData() as $f) {
            [$currentBag, $contains] = explode(' contain ', $f);
            $currentBag = trim(str_replace(['bags', 'bag'], '', $currentBag));
            $contains = str_replace(['bags', 'bag'], '', $contains);
            $list = explode(', ', substr($contains, 0, -1));

            foreach ($list as $l) {
                if ('no other' !== trim($l)) {
                    $q = trim(substr($l, 0, 1));
                    $bag = trim(substr($l, 1));
                    $this->inputs[$currentBag][$bag] = $q;
                    $this->graph[$bag][] = $currentBag;
                }
            }
        }
    }

    public function part1(): int
    {
        return count($this->bfs($this->graph, $this->goldBag));
    }

    public function part2(): int
    {
        return $this->cost($this->inputs, $this->goldBag) - 1;
    }

    private function bfs($graph, $start): array
    {
        $queue = new \SplQueue();
        // Enqueue the path
        $queue->enqueue($start);
        $visited = [$start];
        $path = [];
        while ($queue->count() > 0) {
            $node = $queue->dequeue();
            if (isset($graph[$node])) {
                foreach ($graph[$node] as $neighbour) {
                    if (!in_array($neighbour, $visited)) {
                        $visited[] = $neighbour;

                        $path[] = $neighbour;

                        $queue->enqueue($neighbour);
                    }
                }
            }
        }

        return $path;
    }

    private function cost($graph, $node): int
    {
        $cost = 1;
        if (isset($graph[$node])) {
            foreach ($graph[$node] as $neighbor => $q) {
                $cost += $q * $this->cost($graph, $neighbor);
            }

            return $cost;
        } else {
            return 1;
        }
    }
}
