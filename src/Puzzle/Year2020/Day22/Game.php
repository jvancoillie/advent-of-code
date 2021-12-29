<?php

namespace App\Puzzle\Year2020\Day22;

class Game
{
    public function __construct(private array $deckPlayers)
    {
    }

    public function playPart1(): int
    {
        $player1 = clone $this->deckPlayers[1];
        $player2 = clone $this->deckPlayers[2];
        $round = 0;
        while ($player1->hasCards() && $this->deckPlayers[2]->hasCards()) {
            ++$round;
            $cardPlayer1 = $player1->nextCard();
            $cardPlayer2 = $player2->nextCard();
            if ($cardPlayer1 > $cardPlayer2) {
                $player1->addCard($cardPlayer1)->addCard($cardPlayer2);
            } elseif ($cardPlayer2 > $cardPlayer1) {
                $player2->addCard($cardPlayer2)->addCard($cardPlayer1);
            }
        }

        return $player1->hasCards() ? $player1->getPoints() : $player2->getPoints();
    }

    public function playPart2(): int
    {
        $player1 = clone $this->deckPlayers[1];
        $player2 = clone $this->deckPlayers[2];

        $winner = $this->playRecurseGame($player1, $player2);

        return $winner->getPoints();
    }

    public function playRecurseGame(Deck $player1, Deck $player2, $game = 0): Deck
    {
        $player1Rounds = $player2Rounds = [];
        $round = 1;
        ++$game;

        while (true) {
            if (!$player1->hasCards()) {
                return $player2;
            }

            if (!$player2->hasCards()) {
                return $player1;
            }
            $hash1 = $player1->hashDeck();
            $hash2 = $player2->hashDeck();
            if (in_array($hash1, $player1Rounds) || in_array($hash2, $player2Rounds)) {
                return $player1;
            }

            $player1Rounds[] = $hash1;
            $player2Rounds[] = $hash2;

            $cardPlayer1 = $player1->nextCard();
            $cardPlayer2 = $player2->nextCard();

            if ($player1->countCards() >= $cardPlayer1 && $player2->countCards() >= $cardPlayer2) {
                $subPlayer1 = clone $player1;
                $subPlayer2 = clone $player2;
                $subPlayer1->setDeckLimit($cardPlayer1);
                $subPlayer2->setDeckLimit($cardPlayer2);

                $recurseWinner = $this->playRecurseGame($subPlayer1, $subPlayer2, $game);
                if (1 === $recurseWinner->getPlayerId()) {
                    $player1->addCard($cardPlayer1)->addCard($cardPlayer2);
                } elseif (2 === $recurseWinner->getPlayerId()) {
                    $player2->addCard($cardPlayer2)->addCard($cardPlayer1);
                }
            } else {
                if ($cardPlayer1 > $cardPlayer2) {
                    $player1->addCard($cardPlayer1)->addCard($cardPlayer2);
                } elseif ($cardPlayer2 > $cardPlayer1) {
                    $player2->addCard($cardPlayer2)->addCard($cardPlayer1);
                }
            }
            ++$round;
        }
    }
}
