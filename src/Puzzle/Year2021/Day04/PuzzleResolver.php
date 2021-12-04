<?php

namespace App\Puzzle\Year2021\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private Bingo $bingo;

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createBingo($input);

        $this->bingo->play();

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output): void
    {
        $ans = $this->getScore($this->bingo->getFirstWinner());

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output): void
    {
        $ans = $this->getScore($this->bingo->getLastWinner());

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function getScore(array $list)
    {
        return array_reduce($list, function ($carry, Board $item) {
            return $carry + $item->score();
        });
    }

    private function createBingo(PuzzleInput $input): void
    {
        $data = array_filter(explode("\n", $input->getData()));

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
