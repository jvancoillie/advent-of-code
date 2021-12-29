<?php

namespace App\Puzzle\Year2020\Day23;

class GameB
{
    private $current;
    private int $length;
    private array $next = [];
    private array $prev = [];
    private int $rounds;

    public function __construct(private $cups)
    {
        $this->rounds = 10000000;
        $this->length = 1000000;

        $this->init();
    }

    public function init(): void
    {
        for ($i = 0; $i < $this->length; ++$i) {
            $this->next[$i] = $i + 1;
            $this->prev[$i] = $i - 1;
        }
        $this->next[$this->length - 1] = 0;
        $this->prev[0] = $this->length - 1;
        $this->current = $this->length - 1;

        foreach ($this->cups as $cup) {
            --$cup;
            $this->next[$this->prev[$cup]] = $this->next[$cup];
            $this->prev[$this->next[$cup]] = $this->prev[$cup];
            $this->next[$cup] = $this->next[$this->current];
            $this->prev[$cup] = $this->current;
            $this->prev[$this->next[$this->current]] = $cup;
            $this->next[$this->current] = $cup;
            $this->current = $cup;
        }

        $this->current = $this->length - 1;
    }

    public function move(): void
    {
        $this->current = $this->next[$this->current];
        $a = $this->next[$this->current];
        $b = $this->next[$a];
        $c = $this->next[$b];
        $this->next[$this->current] = $this->next[$c];
        $this->prev[$this->next[$c]] = $this->current;
        $destination = $this->current - 1 < 0 ? $this->length - 1 : $this->current - 1;

        while ($destination === $a || $destination === $b || $destination === $c) {
            --$destination;
            if ($destination < 0) {
                $destination = $this->length - 1;
            }
        }

        $this->next[$c] = $this->next[$destination];
        $this->prev[$a] = $destination;
        $this->prev[$this->next[$destination]] = $c;
        $this->next[$destination] = $a;
    }

    public function play(): int
    {
        for ($i = 0; $i < $this->rounds; ++$i) {
            $this->move();
        }

        return ($this->next[0] + 1) * ($this->next[$this->next[0]] + 1);
    }
}
