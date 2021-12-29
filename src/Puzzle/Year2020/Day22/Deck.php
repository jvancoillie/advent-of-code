<?php

namespace App\Puzzle\Year2020\Day22;

class Deck
{
    private $cards = [];
    private int $playerId;

    public function __construct($playerId)
    {
        $this->cards = [];
        $this->playerId = (int) $playerId;
    }

    public function addCard($card): static
    {
        $this->cards[] = $card;

        return $this;
    }

    public function nextCard(): int
    {
        return array_shift($this->cards);
    }

    public function hasCards(): bool
    {
        return count($this->cards) > 0;
    }

    public function countCards(): int
    {
        return count($this->cards);
    }

    public function getPoints(): int
    {
        $total = 0;
        foreach (array_reverse($this->cards) as $key => $card) {
            $total += ($key + 1) * $card;
        }

        return $total;
    }

    public function hashDeck(): string
    {
        return implode('-', $this->cards);
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    public function setDeckLimit($limit): void
    {
        $this->cards = array_slice($this->cards, 0, $limit);
    }
}
