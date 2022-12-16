<?php

namespace App\Puzzle\Year2022\Day16;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1651;
    protected static int|string $testPart2Expected = 1707;

    protected static int|string $part1Expected = 1488;
    protected static int|string $part2Expected = 2111;

    private array $valves = [];
    private array $paths = [];
    private array $data = [];
    public const START_AT = 'AA';

    protected function initialize(): void
    {
        $data = $this->getInput()->getArrayData();
        $scan = [];
        foreach ($data as $entry) {
            preg_match('/Valve (?P<valve>.*) has flow rate=(?P<rate>.*); tunnel(s?) lead(s?) to valve(s?) (?P<to>.*)/', $entry, $m);
            $scan[$m['valve']] = ['rate' => (int) $m['rate'], 'to' => explode(', ', $m['to'])];
            if ($m['rate'] > 0) {
                $this->valves[$m['valve']] = $m['valve'];
            }
        }

        $this->createPath($scan);
        $this->data = $scan;
    }

    public function part1()
    {
        return $this->getPressure($this->valves);
    }

    public function part2()
    {
        return $this->getPressure($this->valves, 26, true);
    }

    public function getPressure(array $toOpen, $time = 30, $elephant = false, $position = self::START_AT)
    {
        $pressures = [0];
        foreach ($toOpen as $next) {
            $remaining = array_filter($toOpen, fn ($e) => $next !== $e && $this->paths[$position][$next] <= $time);
            $cost = $this->paths[$position][$next];
            $t = ($time - $cost - 1);
            $pressures[] = ($t * $this->data[$next]['rate']) + $this->getPressure($remaining, $t, $elephant, $next);
            if ($elephant) {
                $pressures[] = $this->getPressure($toOpen, 26);
            }
        }

        return max($pressures);
    }

    private function createPath(array $graph)
    {
        foreach ($graph as $from => $data) {
            foreach ($graph as $to => $d) {
                // $this->paths[$from][$to] = $this->path($graph, $from, $to);
                $this->paths[$from][$to] = count(array_slice($this->path($graph, $from, $to), 1));
            }
        }
    }

    private function path(array $graph, string $start, string $end)
    {
        $queue = new \SplQueue();
        // Enqueue the path
        $queue->enqueue([$start]);
        $visited = [$start];
        while ($queue->count() > 0) {
            $path = $queue->dequeue();
            // Get the last node on the path
            // so we can check if we're at the end
            $node = $path[sizeof($path) - 1];

            if ($node === $end) {
                return $path;
            }

            foreach ($graph[$node]['to'] as $neighbor) {
                if (!in_array($neighbor, $visited)) {
                    $visited[] = $neighbor;
                    // Build new path appending the neighbour then and enqueue it
                    $new_path = $path;
                    $new_path[] = $neighbor;

                    $queue->enqueue($new_path);
                }
            }
        }

        return false;
    }
}
