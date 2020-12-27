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
        $this->graph[$from][$from] = 0;
        $this->graph[$to][$to] = 0;
        $this->setComputed(false);
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

            if ($total == $this->shortestDistance) {
                $this->shortestPaths[] = $perms;
            }
        }

        $this->setComputed(true);
    }

    /**
     * @param $items
     * @param array $perms
     * @return mixed
     */
    private function permutations($items, $perms = [])
    {
        static $permutations;

        if (empty($items)) {
            $permutations[] = $perms;
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newItems = $items;
                $newPerms = $perms;
                list($foo) = array_splice($newItems, $i, 1);
                array_unshift($newPerms, $foo);
                $this->permutations($newItems, $newPerms);
            }
        }

        return $permutations;
    }

    /**
     * @return array
     */
    public function getShortestPath()
    {
        $this->compute();

        return $this->shortestPath;
    }

    /**
     * @return array
     */
    public function getShortestPaths()
    {
        $this->compute();

        return $this->shortestPaths;
    }

    /**
     * @return null
     */
    public function getShortestDistance()
    {
        $this->compute();

        return $this->shortestDistance;
    }

    /**
     * @return array
     */
    public function getRoutes()
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
}