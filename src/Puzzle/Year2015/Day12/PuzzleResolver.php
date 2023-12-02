<?php

namespace App\Puzzle\Year2015\Day12;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 15;
    protected static int|string $testPart2Expected = 15;

    protected static int|string $part1Expected = 119433;
    protected static int|string $part2Expected = 68466;

    public function part1()
    {
        $decoded = json_decode($this->getInput()->getData(), null, 512, JSON_THROW_ON_ERROR);

        return $this->sumNumbers($decoded);
    }

    public function part2()
    {
        $decoded = json_decode($this->getInput()->getData(), null, 512, JSON_THROW_ON_ERROR);

        return $this->sumNumbers($decoded, 'red');
    }

    /**
     * @psalm-param 'red'|null $excluded
     */
    public function sumNumbers($array, string $excluded = null)
    {
        $sum = 0;
        foreach ($array as $item) {
            if (is_object($item)) {
                $array = (array) $item;
                if (null === $excluded) {
                    $sum += $this->sumNumbers($array, $excluded);
                } elseif (!in_array($excluded, $array)) {
                    $sum += $this->sumNumbers($array, $excluded);
                }
            } elseif (is_array($item)) {
                $sum += $this->sumNumbers($item, $excluded);
            } elseif (is_numeric($item)) {
                $sum += $item;
            }
        }

        return $sum;
    }
}
