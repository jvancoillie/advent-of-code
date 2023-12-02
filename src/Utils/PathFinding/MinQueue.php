<?php

namespace App\Utils\PathFinding;

class MinQueue implements \Countable
{
    private object $queue;

    private array $register = [];

    /**
     * MinQueue constructor.
     */
    public function __construct()
    {
        $this->queue = new class() extends \SplPriorityQueue {
            public function compare($p, $q): int
            {
                return $q <=> $p;
            }
        };

        $this->register = []; // new \SplObjectStorage();
    }

    public function insert($value, $priority)
    {
        $this->queue->insert($value, $priority);
        $this->register[$value] = $value;
    }

    public function extract()
    {
        $value = $this->queue->extract();
        unset($this->register[$value]);

        return $value;
    }

    public function contains($value)
    {
        return isset($this->register[$value]);
    }

    public function count(): int
    {
        return count($this->queue);
    }
}
