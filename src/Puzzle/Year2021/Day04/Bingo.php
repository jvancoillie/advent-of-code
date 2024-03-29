<?php

namespace App\Puzzle\Year2021\Day04;

class Bingo
{
    /**
     * @var Board[]
     */
    private array $boards;

    /**
     * @var Board[][]
     */
    private array $leaderBoard = [];

    public function __construct(private array $numbers)
    {
    }

    public function addBoard(Board $board): void
    {
        $this->boards[] = $board;
    }

    public function play(): void
    {
        $boards = $this->boards;

        foreach ($this->numbers as $number) {
            $remaining = [];

            foreach ($boards as $board) {
                $board->play($number);

                if ($board->win()) {
                    $this->leaderBoard[$number][] = $board;
                    continue;
                }

                $remaining[] = $board;
            }

            $boards = $remaining;
        }
    }

    public function getFirstWinner(): array
    {
        return $this->leaderBoard[array_key_first($this->leaderBoard)] ?? [];
    }

    public function getLastWinner(): array
    {
        return $this->leaderBoard[array_key_last($this->leaderBoard)] ?? [];
    }
}
