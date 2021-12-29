<?php

namespace App\Puzzle\Year2020\Day23;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/23
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 67384529;
    protected static int|string $testPart2Expected = 149245887792;

    protected static int|string $part1Expected = 28946753;
    protected static int|string $part2Expected = 519044017360;

    public function part1(): int
    {
        $input = array_map('intval', str_split($this->getInput()->getData()));

        $game = new GameA($input);

        return $game->play();
    }

    public function part2(): int
    {
        $input = array_map('intval', str_split($this->getInput()->getData()));

        $game = new GameB($input);

        return $game->play();
    }
}
