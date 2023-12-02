<?php

namespace App\Puzzle\Year2016\Day13;

class MinQueue implements \Countable
{
    private object $queue;

    private \SplObjectStorage $register;

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

        $this->register = new \SplObjectStorage();
    }

    /**
     * @param object $value
     */
    public function insert($value, $priority)
    {
        $this->queue->insert($value, $priority);
        $this->register->attach($value);
    }

    public function extract()
    {
        $value = $this->queue->extract();
        $this->register->detach($value);

        return $value;
    }

    public function contains($value)
    {
        return $this->register->contains($value);
    }

    public function count()
    {
        return count($this->queue);
    }
}
