<?php

namespace App\Puzzle\Year2016\Day05;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/5
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
        $i = 0;
        $salt = 0;
        $string = $input->getData();
        $password = '';
        while ($i < 8) {
            ++$salt;
            $md5 = md5($string.$salt);
            if (str_starts_with($md5, '00000')) {
                $password .= $md5[5];
                ++$i;
            }
        }

        $ans = $password;

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $i = 0;
        $salt = 0;
        $string = $input->getData();
        $password = [];
        while ($i < 8) {
            ++$salt;
            $md5 = md5($string.$salt);
            if (str_starts_with($md5, '00000')) {
                $index = $md5[5];
                if ($index < 8 && !isset($password[$index])) {
                    ++$i;
                    $password[$index] = $md5[6];
                }
            }
        }

        ksort($password);
        $ans = implode('', $password);

        $output->writeln("<info>Part 2 : $ans</info>");
    }
}
