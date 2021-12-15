<?php

namespace App\Utils\PathFinding;

class Dijkstra
{
    public function __construct(private array $graph)
    {
    }

    public function findPath($src, $dst, $detailedResult = false): array
    {
        $queue = new MinQueue();
        $distance = [];
        $path = [];

        $queue->insert($src, 0);
        $distance[$src] = 0;

        while (count($queue) > 0) {
            $u = $queue->extract();

            if ($u === $dst) {
                $path = $this->buildPath($dst, $path);

                return $detailedResult ? $this->buildDetailedPath($path) : $path;
            }

            foreach ($this->graph[$u] as $v => $dist) {
                $alt = $distance[$u] + $dist;
                $best = $distance[$v] ?? INF;

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

    private function buildPath($dst, array $path): array
    {
        $result = [$dst];

        while (isset($path[$dst]) && null !== $path[$dst]) {
            $src = $path[$dst];
            $result[] = $src;
            $dst = $src;
        }

        return array_reverse($result);
    }

    private function buildDetailedPath(array $path): array
    {
        $pathWeight = 0;
        $previousNode = array_shift($path);
        $detailedResult = [[
            'node' => $previousNode,
            'weight' => 0,
            'path_weight' => 0,
        ]];

        while (!empty($path)) {
            $currentNode = array_shift($path);
            $weight = $this->graph[$previousNode][$currentNode];
            $pathWeight += $weight;
            $detailedResult[] = [
                'node' => $currentNode,
                'weight' => $weight,
                'path_weight' => $pathWeight,
            ];
            $previousNode = $currentNode;
        }

        return $detailedResult;
    }
}
