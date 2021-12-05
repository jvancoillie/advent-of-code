<?php

namespace App\Utils\PathFinding;

use App\Utils\GraphInterface;
use App\Utils\NodeInterface;

class Dijkstra
{
    public function __construct(private GraphInterface $graph)
    {
    }

    /**
     * see: https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm#Using_a_priority_queue.
     */
    public function findPath(NodeInterface $src, NodeInterface $dst): array
    {
        // setup
        $queue = new MinQueue();
        $distance = new \SplObjectStorage();
        $path = new \SplObjectStorage();

        // init
        $queue->insert($src, 0);
        $distance[$src] = 0;

        while (count($queue) > 0) {
            $u = $queue->extract();
            if ($u === $dst) {
                return $this->buildPath($dst, $path);
            }

            foreach ($this->graph->getNeighbors($u) as $v) {
                $alt = $distance[$u] + $this->graph->getDistance($u, $v);
                $best = isset($distance[$v]) ? $distance[$v] : INF;

                if ($alt < $best) {
                    $distance[$v] = $alt;
                    $path[$v] = $u;

                    if (!$queue->contains($v)) {
                        $queue->insert($v, $alt);
                    }
                }
            }
        }

        throw new \LogicException('No path found.');
    }

    /**
     * @param object $dst
     */
    private function buildPath($dst, \SplObjectStorage $path): array
    {
        $result = [$dst];

        while (isset($path[$dst]) && null !== $path[$dst]) {
            $src = $path[$dst];
            $result[] = $src;
            $dst = $src;
        }

        return array_reverse($result);
    }
}
