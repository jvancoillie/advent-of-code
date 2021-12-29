<?php

namespace App\Puzzle\Year2020\Day22;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/22
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 306;
    protected static int|string $testPart2Expected = 291;

    protected static int|string $part1Expected = 34664;
    protected static int|string $part2Expected = 32018;

    private Game $game;

    protected function initialize(): void
    {
        $deckPlayers = [];

        foreach (explode("\n\n", $this->getInput()->getData()) as $player) {
            $playerId = 0;
            foreach (explode("\n", $player) as $line) {
                if (preg_match("/^Player\s(?<id>.*):$/", $line, $matches)) {
                    $playerId = $matches['id'];
                    $deckPlayers[$playerId] = new Deck($playerId);
                } else {
                    $card = (int) $line;
                    $deckPlayers[$playerId]->addCard($card);
                }
            }
        }

        $this->game = new Game($deckPlayers);
    }

    public function part1(): int
    {
        return $this->game->playPart1();
    }

    public function part2(): int
    {
        return $this->game->playPart2();
    }
}
