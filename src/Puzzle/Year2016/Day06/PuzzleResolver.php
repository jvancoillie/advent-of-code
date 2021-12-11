<?php

namespace App\Puzzle\Year2016\Day06;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/6
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 'easter';
    protected static int|string $testPart2Expected = 'advent';

    protected static int|string $part1Expected = 'cyxeoccr';
    protected static int|string $part2Expected = 'batwpask';

    public function part1()
    {
        $score = [];
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            foreach (str_split($line) as $key => $value) {
                if (!isset($score[$key])) {
                    $score[$key] = [];
                }

                if (isset($score[$key][$value])) {
                    ++$score[$key][$value];
                } else {
                    $score[$key][$value] = 1;
                }
            }
        }

        $ans = '';
        foreach ($score as $values) {
            arsort($values);
            $ans .= array_key_first($values);
        }

        return $ans;
    }

    public function part2()
    {
        $score = [];
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            foreach (str_split($line) as $key => $value) {
                if (!isset($score[$key])) {
                    $score[$key] = [];
                }

                if (isset($score[$key][$value])) {
                    ++$score[$key][$value];
                } else {
                    $score[$key][$value] = 1;
                }
            }
        }

        $ans = '';
        foreach ($score as $values) {
            asort($values);
            $ans .= array_key_first($values);
        }

        return $ans;
    }
}
