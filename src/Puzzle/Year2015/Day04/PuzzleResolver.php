<?php

namespace App\Puzzle\Year2015\Day04;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 609043;
    protected static int|string $testPart2Expected = 6742839;

    protected static int|string $part1Expected = 254575;
    protected static int|string $part2Expected = 1038736;

    public function part1()
    {
        $i = 0;
        $md5 = '';
        $string = $this->getInput()->getData();
        while (!str_starts_with($md5, '00000')) {
            ++$i;
            $md5 = md5($string.$i);
        }

        return $i;
    }

    public function part2()
    {
        $i = 0;
        $md5 = '';
        $string = $this->getInput()->getData();
        while (!str_starts_with($md5, '000000')) {
            ++$i;
            $md5 = md5($string.$i);
        }

        return $i;
    }
}
