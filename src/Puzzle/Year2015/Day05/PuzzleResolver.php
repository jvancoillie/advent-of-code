<?php

namespace App\Puzzle\Year2015\Day05;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 258;
    protected static int|string $part2Expected = 53;

    public function part1()
    {
        $patternVowels = '/(?:[aeiou][^aeiou]*){3}/';
        $patternTwice = '/(\w)\1/';
        $patternTReserved = '/(ab|cd|pq|xy)/';
        $nice = 0;

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            if (preg_match($patternVowels, $line) && preg_match($patternTwice, $line) && !preg_match($patternTReserved, $line)) {
                ++$nice;
            }
        }

        return $nice;
    }

    public function part2()
    {
        $patternPair = '/(..).*?\1/';
        $patternRepeat = '/(.).\1/';
        $nice = 0;

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            if (preg_match($patternPair, $line) && preg_match($patternRepeat, $line)) {
                ++$nice;
            }
        }

        return $nice;
    }
}
