<?php

namespace App\Puzzle\Year2021\Day08;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $count = 0;
    private $sum = 0;

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $data = explode("\n", $input->getData());
        $this->guess($data);

        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $this->count;

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = $this->sum;

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function guess($data)
    {
        $sum = 0;
        foreach ($data as $line) {
            $e = explode('|', $line);

            $list = explode(' ', trim($e[0]));
            $output = explode(' ', trim($e[1]));

            $numbers = [1 => [], 4 => [], 7 => [], 8 => []];

            usort($list, function ($a, $b) {
                return strlen($a) <=> strlen($b);
            });
            // extract 1 4 7 8
            foreach ($list as $l) {
                $len = strlen($l);
                $value = str_split($l);
                sort($value);

                switch ($len) {
                    case 2:
                        $numbers[1] = $value;
                        break;
                    case 4:
                        $numbers[4] = $value;
                        break;
                    case 3:
                        $numbers[7] = $value;
                        break;
                    case 7:
                        $numbers[8] = $value;
                        break;
                }
            }

            $result = '';
            foreach ($output as $l) {
                $len = strlen($l);
                $value = str_split($l);

                // parse output based on guessed numbers
                switch ($len) {
                    case 2:
                        $result .= '1';
                        ++$this->count;
                        break;
                    case 4:
                        $result .= '4';
                        ++$this->count;
                        break;
                    case 3:
                        $result .= '7';
                        ++$this->count;
                        break;
                    case 7:
                        $result .= '8';
                        ++$this->count;
                        break;
                    case 6:
                        // here guess number 0 6 9
                        if (!array_diff($numbers[4], $value)) {
                            $result .= '9';
                        } elseif (!array_diff($numbers[7], $value)) {
                            $result .= '0';
                        } else {
                            $result .= '6';
                        }
                        break;
                    case 5:
                        // here guess number 2 3 5
                        if (!array_diff($numbers[7], $value)) {
                            $result .= '3';
                        } elseif (!array_diff(array_diff($numbers[4], $numbers[7]), $value)) {
                            $result .= '5';
                        } else {
                            $result .= '2';
                        }
                        break;
                }
            }
            $sum += (int) $result;
        }

        $this->sum = $sum;
    }
}
