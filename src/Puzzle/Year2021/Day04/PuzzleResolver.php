<?php

namespace App\Puzzle\Year2021\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4512;
    protected static int|string $testPart2Expected = 1924;

    protected static int|string $part1Expected = 38594;
    protected static int|string $part2Expected = 21184;

    private Bingo $bingo;

    public function main()
    {
        $this->createBingo($this->getInput());

        $this->bingo->play();
    }

    public function part1(): int
    {
        return $this->getScore($this->bingo->getFirstWinner());
    }

    public function part2(): int
    {
        return $this->getScore($this->bingo->getLastWinner());
    }

    private function getScore(array $list)
    {
        return array_reduce($list, function ($carry, Board $item) {
            return $carry + $item->score();
        });
    }

    private function createBingo(PuzzleInput $input): void
    {
        $data = array_filter(explode("\n", $this->getInput()->getData()));

        $numbers = array_map('intval', explode(',', array_shift($data)));

        $this->bingo = new Bingo($numbers);

        $dataGrids = array_chunk($data, 5);

        foreach ($dataGrids as $dataGrid) {
            $grid = [];
            foreach ($dataGrid as $entry) {
                $grid[] = array_map('intval', preg_split('/\s+/', trim($entry)));
            }

            $this->bingo->addBoard(new Board($grid));
        }
    }
}
