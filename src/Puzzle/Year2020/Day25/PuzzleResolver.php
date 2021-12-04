<?php

namespace App\Puzzle\Year2020\Day25;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $data = explode("\n", $input->getData());

        $cardSubjectNumber = (int) $data[0];
        $doorSubjectNumber = (int) $data[1];

        $doorLoopSize = $this->findLoopSize($doorSubjectNumber);
        $cardKey = $this->transformNumber($cardSubjectNumber, $doorLoopSize);

        $output->writeln("<info>Part 1 : $cardKey</info>");
    }

    /**
     * @psalm-return 0|positive-int
     */
    public function findLoopSize(int $key): int
    {
        $i = 0;
        $subjectNumber = 7;
        $value = 1;

        while ($key !== $value) {
            ++$i;
            $value *= $subjectNumber;
            $value %= 20201227;
        }

        return $i;
    }

    public function transformNumber(int $subjectNumber, $loopSize)
    {
        $n = $subjectNumber;
        $value = 1;
        for ($i = 0; $i < $loopSize; ++$i) {
            $value = $value * $n;
            $value = $value % 20201227;
        }

        return $value;
    }
}
