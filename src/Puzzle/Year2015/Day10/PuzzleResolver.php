<?php

namespace App\Puzzle\Year2015\Day10;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @package App\Puzzle\Year2015\Day10
 *
 * @see https://en.wikipedia.org/wiki/Look-and-say_sequence
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output)
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $input->getData();

        for ($i = 1; $i <= 40; $i++) {
            $ans = $this->lookAndSay($ans);
        }
        $ans = strlen($ans);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $input->getData();

        for ($i = 1; $i <= 50; $i++) {
            $ans = $this->lookAndSay($ans);
        }
        $ans = strlen($ans);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function lookAndSay($str)
    {
        return preg_replace_callback(
            '#(.)\1*#',
            function ($matches) {
                return strlen($matches[0]).$matches[1];
            },
            $str
        );
    }
}