<?php

namespace App\Puzzle\Year2016\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = '18f47a30';
    protected static int|string $testPart2Expected = '05ace8e3';

    protected static int|string $part1Expected = 'd4cd2ee1';
    protected static int|string $part2Expected = 'f2c730e5';

    public function part1()
    {
        $i = 0;
        $salt = 0;
        $string = $this->getInput()->getData();
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

        return $ans;
    }

    public function part2()
    {
        $i = 0;
        $salt = 0;
        $string = $this->getInput()->getData();
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

        return $ans;
    }
}
