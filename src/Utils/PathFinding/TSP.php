<?php

namespace App\Utils\PathFinding;


/**
 * Class TSP
 * @package App\Utils\PathFinding
 * @see https://en.wikipedia.org/wiki/Travelling_salesman_problem
 */
class TSP
{
    /**
     * @var array
     */
    private $shortestPath = [];
    /**
     * @var array
     */
    private $shortestPaths = [];
    /**
     * @var null
     */
    private $shortestDistance = null;

    /**
     * @var array
     */
    private $longestPath = [];
    /**
     * @var array
     */
    private $longestPaths = [];
    /**
     * @var null
     */
    private $longestDistance = null;

    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var array
     */
    private $graph;

    /**
     * @var false
     */
    private $computed = false;

    /**
     * TSP constructor.
     * @param array $graph
     */
    public function __construct(array $graph = [])
    {
        $this->graph = $graph;
    }

    /**
     * @param $from
     * @param $to
     * @param $distance
     */
    public function add($from, $to, $distance)
    {
        $this->graph[$from][$to] = $distance;
        $this->graph[$to][$from] = $distance;
        $this->reset();
    }

    /**
     * Compute all routes
     */
    public function compute()
    {
        if ($this->isComputed()) {
            return;
        }
        $keys = array_keys($this->graph);
        $this->routes = $this->permutations($keys);
        foreach ($this->routes as $key => $perms) {
            $total = 0;
            for ($i = 0; $i < count($perms) - 1; $i++) {
                $total += $this->graph[$perms[$i]][$perms[$i + 1]];
            }

            $this->routes[$key]['distance'] = $total;

            if ($total < $this->shortestDistance || $this->shortestDistance === null) {
                $this->shortestDistance = $total;
                $this->shortestPath = $perms;
                $this->shortestPaths = [];
            }

            if ($total > $this->longestDistance || $this->longestDistance === null) {
                $this->longestDistance = $total;
                $this->longestPath = $perms;
                $this->longestPaths = [];
            }

            if ($total == $this->shortestDistance) {
                $this->shortestPaths[] = $perms;
            }
            if ($total == $this->longestDistance) {
                $this->longestPaths[] = $perms;
            }
        }

        $this->setComputed(true);
    }

    /**
     * @param $items
     * @param array $perms
     * @return mixed
     */
    private function permutations($items): array
    {
        if (count($items) === 0) {
            return [[]];
        }

        $firstItem = array_shift($items);
        $permsWithoutFirst = $this->permutations($items);
        $allPermutations = [];

        foreach ($permsWithoutFirst as $perms){
            for($i=0; $i<=count($perms); $i++){
                $permsWithFirst = array_merge(array_slice($perms, 0, $i), [$firstItem], array_slice($perms, $i));
                $allPermutations[] = $permsWithFirst;
            }
        }

        return $allPermutations;
    }

    /**
     * @return array
     */
    public function getShortestPath(): array
    {
        $this->compute();

        return $this->shortestPath;
    }

    /**
     * @return array
     */
    public function getShortestPaths(): array
    {
        $this->compute();

        return $this->shortestPaths;
    }

    /**
     * @return int|null
     */
    public function getShortestDistance(): ?int
    {
        $this->compute();

        return $this->shortestDistance;
    }

    /**
     * @return array
     */
    public function getLongestPath(): array
    {
        $this->compute();

        return $this->longestPath;
    }

    /**
     * @return array
     */
    public function getLongestPaths(): array
    {
        $this->compute();

        return $this->longestPaths;
    }

    /**
     * @return int|null
     */
    public function getLongestDistance()
    {
        $this->compute();

        return $this->longestDistance;
    }

    /**
     * @return array
     */
    public function getGraph(): array
    {
        return $this->graph;
    }


    /**
     * @return array
     */
    public function getRoutes(): array
    {
        $this->compute();

        return $this->routes;
    }

    /**
     * @return false
     */
    public function isComputed(): bool
    {
        return $this->computed;
    }

    /**
     * @param false $computed
     * @return TSP
     */
    public function setComputed(bool $computed): TSP
    {
        $this->computed = $computed;

        return $this;
    }

    public function reset()
    {
        $this->computed = false;
        $this->longestDistance = null;
        $this->shortestDistance = null;
        $this->longestPaths = [];
        $this->longestPath = [];
        $this->shortestPath = [];
        $this->shortestPaths = [];
        $this->routes = [];
    }
}