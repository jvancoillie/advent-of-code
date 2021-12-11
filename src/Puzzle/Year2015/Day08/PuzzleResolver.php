<?php

namespace App\Puzzle\Year2015\Day08;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 12;
    protected static int|string $testPart2Expected = 19;

    protected static int|string $part1Expected = 1333;
    protected static int|string $part2Expected = 2046;

    public function part1()
    {
        $ans = 0;
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $ans += 2;
            preg_match_all('/(\\\.)/', $line, $m);
            foreach ($m[0] as $r) {
                $ans += ('\x' == $r) ? 3 : 1;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $ans += strlen(addslashes($line)) + 2 - strlen($line);
        }

        return $ans;
    }
}
