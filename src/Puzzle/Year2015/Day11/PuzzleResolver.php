<?php

namespace App\Puzzle\Year2015\Day11;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $password = $input->getData();

        $password = $this->part1($password, $output);

        $this->part2($password, $output);
    }

    public function part1(string $password, OutputInterface $output)
    {
        $password = $this->nextPassword($password);

        $output->writeln("<info>Part 1 : $password</info>");

        return $password;
    }

    public function part2(string $password, OutputInterface $output)
    {
        $password = $this->nextPassword($password);

        $output->writeln("<info>Part 2 : $password</info>");
    }

    public function nextPassword($password)
    {
        do {
            ++$password;
        } while (!$this->isValidPassword($password));

        return $password;
    }

    public function isValidPassword($password)
    {
        $arr = str_split($password);

        for ($i = 0; $i < count($arr) - 2; ++$i) {
            if (ord($arr[$i + 1]) === ord($arr[$i]) + 1 && ord($arr[$i + 2]) === ord($arr[$i]) + 2) {
                return (1 !== preg_match('/[iol]/', $password))
                    && (1 === preg_match('/(.)\\1.*(.)\\2/', $password));
            }
        }

        return false;
    }
}
