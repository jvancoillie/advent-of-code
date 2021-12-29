<?php

namespace App\Puzzle\Year2020\Day23;

class GameA
{
    private int $length;
    private int $rounds;

    public function __construct(private $cups)
    {
        $this->length = count($cups);
        $this->rounds = 100;
    }

    public function move(): void
    {
        $cups = $this->cups;
        $currentNumber = array_shift($cups);
        $pickUp = array_slice($cups, 0, 3);
        $remaining = array_slice($cups, 3);
        $destination = $currentNumber;

        do {
            --$destination;
            if (0 === $destination) {
                $destination = $this->length;
            }
        } while (!in_array($destination, $remaining));

        $nextPosition = array_search($destination, $remaining);
        $part1 = array_slice($remaining, 0, $nextPosition + 1);
        $part2 = array_slice($remaining, $nextPosition + 1);
        $this->cups = array_merge($part1, $pickUp, $part2, [$currentNumber]);
    }

    public function play(): int
    {
        for ($i = 1; $i <= $this->rounds; ++$i) {
            $this->move();
        }

        $p = array_search(1, $this->cups);
        $part1 = array_slice($this->cups, 0, $p);
        $part2 = array_slice($this->cups, $p + 1);
        if (empty($part1) && empty($part2)) {
            return 0;
        }

        return (int) (implode('', $part2).implode('', $part1));
    }
}
