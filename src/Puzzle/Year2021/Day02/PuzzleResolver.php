<?php

namespace App\Puzzle\Year2021\Day02;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/2
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
        $ans = $this->navigate(explode("\n", $input->getData()));

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = $this->navigateWithAim(explode("\n", $input->getData()));

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function navigate(array $data): int
    {
        $depth = $horizontal = 0;
        foreach ($data as $moves) {
            [$action, $dist] = explode(' ', $moves);
            switch ($action) {
                case 'forward':
                    $horizontal += (int) $dist;
                    break;
                case 'down':
                    $depth += (int) $dist;
                    break;
                case 'up':
                    $depth -= (int) $dist;
                    break;
            }
        }

        return $depth * $horizontal;
    }

    private function navigateWithAim(array $data): int
    {
        $depth = $horizontal = $aim = 0;
        foreach ($data as $moves) {
            [$action, $dist] = explode(' ', $moves);
            switch ($action) {
                case 'forward':
                    $horizontal += (int) $dist;
                    $depth += $aim * (int) $dist;
                    break;
                case 'down':
                    $aim += (int) $dist;
                    break;
                case 'up':
                    $aim -= (int) $dist;
                    break;
            }
        }

        return $depth * $horizontal;
    }
}
