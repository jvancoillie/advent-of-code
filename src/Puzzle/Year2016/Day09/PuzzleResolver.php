<?php

namespace App\Puzzle\Year2016\Day09;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/9
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
        $ans = $this->decompress($input->getData(), false);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = $this->decompress($input->getData());

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function decompress(string $sequence, bool $recurse = true): float|int
    {
        $decompressedLength = 0;
        while (false !== $pos = strpos($sequence, '(')) {
            $decompressedLength += strlen(substr($sequence, 0, $pos));

            if (preg_match("/\((\d+)x(\d+)\)(.+)/", $sequence, $matches)) {
                [ ,$subsequentLength, $repeated, $remaining] = $matches;

                $stringLength = ($recurse) ? $this->decompress(substr($remaining, 0, (int) $subsequentLength)) : strlen(substr($remaining, 0, (int) $subsequentLength));
                $decompressedLength += (int) $repeated * $stringLength;

                $sequence = substr($remaining, (int) $subsequentLength);
            }
        }

        return $decompressedLength + strlen($sequence);
    }
}
