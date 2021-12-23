<?php

namespace App\Puzzle\Year2021\Day21;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/21
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 739785;
    protected static int|string $testPart2Expected = 444356092776315;

    protected static int|string $part1Expected = 805932;
    protected static int|string $part2Expected = 133029050096658;

    private array $memo;
    private array $players;

    protected function initialize(): void
    {
        $data = explode("\n", $this->getInput()->getData());

        foreach ($data as $i => $line) {
            $playerId = $i + 1;
            $this->players[$playerId]['position'] = explode(' ', $line)[4];
            $this->players[$playerId]['score'] = 0;
        }
    }

    public function part1(): int
    {
        return $this->deterministicDice($this->players[1], $this->players[2]);
    }

    public function part2(): int
    {
        return max($this->diracDice($this->players[1], $this->players[2]));
    }

    public function deterministicDice($player, $opponent, $dice = 1): int
    {
        if ($opponent['score'] >= 1000) {
            return ($dice - 1) * $player['score'];
        }

        $dices = 3 * $dice + 3;
        $pos = (($player['position'] + $dices) % 10);
        $pos = (0 === $pos) ? 10 : $pos;

        $player['position'] = $pos;
        $player['score'] += $pos;

        return $this->deterministicDice($opponent, $player, $dice + 3);
    }

    public function diracDice($player, $opponent)
    {
        if ($opponent['score'] >= 21) {
            return [0, 1];
        }

        if (isset($this->memo[$key = json_encode([$player, $opponent], JSON_THROW_ON_ERROR)])) {
            return $this->memo[$key];
        }

        $universes = [0, 0];

        foreach (range(1, 3) as $dice1) {
            foreach (range(1, 3) as $dice2) {
                foreach (range(1, 3) as $dice3) {
                    $state = $player;
                    $dices = $dice1 + $dice2 + $dice3;
                    // new position
                    $pos = (($player['position'] + $dices) % 10);
                    $pos = (0 === $pos) ? 10 : $pos;
                    $state['position'] = $pos;
                    // new score
                    $state['score'] += $pos;

                    [$win2, $win1] = $this->diracDice($opponent, $state);
                    $universes[0] += $win1;
                    $universes[1] += $win2;
                }
            }
        }

        $this->memo[json_encode([$player, $opponent], JSON_THROW_ON_ERROR)] = $universes;

        return $universes;
    }
}
