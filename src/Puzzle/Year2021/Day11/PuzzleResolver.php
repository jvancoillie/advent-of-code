<?php

namespace App\Puzzle\Year2021\Day11;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private array $directions = [
        [0, 1],
        [0, -1],
        [1, 0],
        [1, -1],
        [1, 1],
        [-1, 0],
        [-1, -1],
        [-1, 1],
    ];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;

        $data = explode("\n", $input->getData());
        $grid = [];

        foreach ($data as $line) {
            $grid[] = str_split($line);
        }

        for ($i = 0; $i < 100; ++$i) {
            [$grid, $flashesCount] = $this->playStep($grid);
            $ans += $flashesCount;
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $data = explode("\n", $input->getData());

        $grid = [];

        foreach ($data as $line) {
            $grid[] = str_split($line);
        }

        $ans = 0;
        while (true) {
            ++$ans;
            [$grid, $flashesCount] = $this->playStep($grid);

            $sum = 0;
            foreach ($grid as $line) {
                $sum += array_sum($line);
            }
            if (0 == $sum) {
                break;
            }
        }

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function playStep($grid): array
    {
        $flashes = [];
        $flashesCount = 0;

        // first increase energy
        for ($y = 0; $y < count($grid); ++$y) {
            for ($x = 0; $x < count($grid[$y]); ++$x) {
                if (9 == $grid[$y][$x]) {
                    $flashes[] = [$y, $x];
                    $grid[$y][$x] = 0;

                    continue;
                }

                ++$grid[$y][$x];
            }
        }
        // play flashes
        while (count($flashes) > 0) {
            [$y, $x] = array_pop($flashes);
            ++$flashesCount;
            foreach ($this->directions as [$dy, $dx]) {
                $nx = $x + $dx;
                $ny = $y + $dy;
                if (isset($grid[$ny][$nx]) && 0 != $grid[$ny][$nx]) {
                    ++$grid[$ny][$nx];

                    if ($grid[$ny][$nx] > 9) {
                        $grid[$ny][$nx] = 0;
                        $flashes[] = [$ny, $nx];
                    }
                }
            }
        }

        return [$grid, $flashesCount];
    }
}
