<?php

namespace App\Puzzle\Year2021\Day08;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 26;
    protected static int|string $testPart2Expected = 61229;

    protected static int|string $part1Expected = 274;
    protected static int|string $part2Expected = 1012089;

    private $count = 0;
    private $sum = 0;

    public function main()
    {
        $data = explode("\n", $this->getInput()->getData());
        $this->guess($data);
    }

    public function part1()
    {
        return $this->count;
    }

    public function part2()
    {
        return $this->sum;
    }

    public function guess($data)
    {
        $sum = 0;
        foreach ($data as $line) {
            $e = explode('|', $line);

            $list = explode(' ', trim($e[0]));
            $output = explode(' ', trim($e[1]));

            $numbers = [1 => [], 4 => [], 7 => [], 8 => []];

            // extract 1 4 7 8
            foreach ($list as $l) {
                $len = strlen($l);
                $value = str_split($l);

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
