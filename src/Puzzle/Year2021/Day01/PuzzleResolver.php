<?php

namespace App\Puzzle\Year2021\Day01;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/1
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
        $ans = $this->countIncrease(explode("\n", $input->getData()), 1);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = $this->countIncrease(explode("\n", $input->getData()), 3);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    /**
     * @param string[] $data
     *
     * @psalm-param non-empty-list<string> $data
     * @psalm-param 1|3 $size
     */
    private function countIncrease(array $data, int $size): int
    {
        $increase = 0;

        for ($i = 0; $i < count($data) - $size; ++$i) {
            if ($data[$i] < $data[$i + $size]) {
                ++$increase;
            }
        }

        return $increase;
    }
}
