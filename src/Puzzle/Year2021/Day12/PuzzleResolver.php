<?php

namespace App\Puzzle\Year2021\Day12;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 226;
    protected static int|string $testPart2Expected = 3509;

    protected static int|string $part1Expected = 3761;
    protected static int|string $part2Expected = 99138;

    private array $graph = [];

    protected function initialize(): void
    {
        $this->graph = $this->createGraph();
    }

    public function part1(): int
    {
        $paths = $this->breadthFirstSearch($this->graph, 'start', 'end', function ($node, $visited): array {
            if (isset($visited[$node])) {
                return [true, $visited];
            }

            if (!ctype_upper($node)) {
                $visited[$node] = 1;
            }

            return [false, $visited];
        });

        return count($paths);
    }

    public function part2(): int
    {
        $paths = $this->breadthFirstSearch($this->graph, 'start', 'end', function ($node, $visited): array {
            if (isset($visited[$node]) && (in_array($node, ['start', 'end']) || !empty(array_filter($visited, fn ($n) => $n > 1)))) {
                return [true, $visited];
            }

            if (!ctype_upper($node)) {
                if (isset($visited[$node])) {
                    ++$visited[$node];
                } else {
                    $visited[$node] = 1;
                }
            }

            return [false, $visited];
        });

        return count($paths);
    }

    public function createGraph(): array
    {
        $data = explode("\n", $this->getInput()->getData());

        $graph = [];
        foreach ($data as $node) {
            preg_match('/(?P<from>.*)-(?P<to>.*)/', $node, $matches);

            $graph[$matches['from']][] = $matches['to'];
            $graph[$matches['to']][] = $matches['from'];
        }

        return $graph;
    }

    public function breadthFirstSearch($graph, $start, $end, callable $neighborsCallback): array
    {
        $paths = [];

        $queue = new \SplQueue();
        $queue->enqueue([[$start], [$start => 1]]);

        while (!$queue->isEmpty()) {
            [$path, $visited] = $queue->dequeue();
            $node = end($path);

            if ($node === $end) {
                $paths[] = $path;
                continue;
            }

            foreach ($graph[$node] as $neighbour) {
                $currentVisited = $visited;

                [$skip, $currentVisited] = $neighborsCallback($neighbour, $currentVisited);

                if ($skip) {
                    continue;
                }

                $new_path = $path;
                $new_path[] = $neighbour;

                $queue->enqueue([$new_path, $currentVisited]);
            }
        }

        return $paths;
    }
}
