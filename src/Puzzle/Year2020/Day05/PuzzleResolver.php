<?php

namespace App\Puzzle\Year2020\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 820;
    protected static int|string $testPart2Expected = 819;

    protected static int|string $part1Expected = 906;
    protected static int|string $part2Expected = 519;

    private array $places;

    public function initialize(): void
    {
        $data = explode("\n", $this->getInput()->getData());

        foreach ($data as $suite) {
            $rowRange = [0, 127];
            foreach (str_split(substr($suite, 0, 7)) as $l) {
                $middle = round(($rowRange[1] - $rowRange[0]) / 2);
                if ('F' === $l) {
                    $rowRange[1] = $rowRange[1] - $middle;
                } else {
                    $rowRange[0] = $rowRange[0] + $middle;
                }
            }

            $colRange = [0, 7];
            foreach (str_split(substr($suite, 7)) as $l) {
                $middle = round(($colRange[1] - $colRange[0]) / 2);

                if ('L' === $l) {
                    $colRange[1] = $colRange[1] - $middle;
                } else {
                    $colRange[0] = $colRange[0] + $middle;
                }
            }

            $places[] = $rowRange[0] * 8 + $colRange[0];
        }
        sort($places);

        $this->places = $places;
    }

    public function part1()
    {
        return max($this->places);
    }

    public function part2()
    {
        $previous = $myPlace = null;

        foreach ($this->places as $key => $value) {
            if (0 === $key) {
                $previous = $value;
                continue;
            }
            if ($value - 1 !== $previous) {
                $myPlace = $value - 1;
            }
            $previous = $value;
        }

        return $myPlace;
    }
}
