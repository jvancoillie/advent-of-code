<?php

namespace App\Puzzle\Year2015\Day25;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/25
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        preg_match('/^To continue, please consult the code grid in the manual.  Enter the code at row (?<row>\d+), column (?<column>\d+).$/', $input->getData(), $matches);
        $targetRow = (int) $matches['row'];
        $targetColumn = (int) $matches['column'];

        $column = $row = $nextRow = 1;
        $multiply = 252533;
        $divide = 33554393;
        $ans = 20151125;

        while ($column !== $targetColumn || $row !== $targetRow) {
            $ans = ($ans * $multiply) % $divide;
            if (1 === $row) {
                $column = 1;
                $row = ++$nextRow;
            } else {
                ++$column;
                --$row;
            }
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $output->writeln('<comment>This event is done :)</comment>');
    }
}
