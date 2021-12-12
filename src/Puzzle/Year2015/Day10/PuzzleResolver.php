<?php

namespace App\Puzzle\Year2015\Day10;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://en.wikipedia.org/wiki/Look-and-say_sequence
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 82350;
    protected static int|string $testPart2Expected = 1166642;

    protected static int|string $part1Expected = 252594;
    protected static int|string $part2Expected = 3579328;

    public function part1()
    {
        $ans = $this->getInput()->getData();

        for ($i = 1; $i <= 40; ++$i) {
            $ans = $this->lookAndSay($ans);
        }

        return strlen($ans);
    }

    public function part2()
    {
        $ans = $this->getInput()->getData();

        for ($i = 1; $i <= 50; ++$i) {
            $ans = $this->lookAndSay($ans);
        }

        return strlen($ans);
    }

    public function lookAndSay($str): string|null
    {
        return preg_replace_callback(
            '#(.)\1*#',
            fn($matches) => strlen($matches[0]).$matches[1],
            $str
        );
    }
}
