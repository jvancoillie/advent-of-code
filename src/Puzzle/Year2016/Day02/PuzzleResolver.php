<?php

namespace App\Puzzle\Year2016\Day02;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $instruction[] = str_split($line);
        }

        $code = $this->getCode(
            $instruction,
            [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8, 9],
            ],
            1,
            1
        );

        $ans = implode('', $code);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $instruction[] = str_split($line);
        }

        $code = $this->getCode(
            $instruction,
            [
                [2 => 1],
                [1 => 2, 2 => 3, 3 => 4],
                [0 => 5, 1 => 6, 2 => 7, 3 => 8, 4 => 9],
                [1 => 'A', 2 => 'B', 3 => 'C'],
                [2 => 'D'],
            ],
            2,
            0
        );

        $ans = implode('', $code);
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function getCode($insctructions, $pad, $y, $x)
    {
        $code = [];
        foreach ($insctructions as $directions) {
            foreach ($directions as $direction) {
                $nx = $x;
                $ny = $y;
                switch ($direction) {
                    case 'U':
                        $ny--;
                        break;
                    case 'D':
                        $ny++;
                        break;
                    case 'R':
                        $nx++;
                        break;
                    case 'L':
                        $nx--;
                        break;
                }
                if (isset($pad[$ny][$nx])) {
                    $y = $ny;
                    $x = $nx;
                }
            }
            $code[] = $pad[$y][$x];
        }

        return $code;
    }
}
