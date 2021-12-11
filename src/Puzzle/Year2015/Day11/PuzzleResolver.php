<?php

namespace App\Puzzle\Year2015\Day11;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 'abcdffaa';
    protected static int|string $testPart2Expected = 'abcdffbb';

    protected static int|string $part1Expected = 'hxbxxyzz';
    protected static int|string $part2Expected = 'hxcaabcc';

    public function part1()
    {
        return $this->nextPassword($this->getInput()->getData());
    }

    public function part2()
    {
        return $this->nextPassword($this->part1());
    }

    public function nextPassword(string $password): string
    {
        do {
            ++$password;
        } while (!$this->isValidPassword($password));

        return $password;
    }

    public function isValidPassword(string $password): bool
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
