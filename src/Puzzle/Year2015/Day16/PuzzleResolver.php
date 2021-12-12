<?php

namespace App\Puzzle\Year2015\Day16;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 40;
    protected static int|string $part2Expected = 241;

    private $tickerTape = [
        'children' => 3,
        'cats' => 7,
        'samoyeds' => 2,
        'pomeranians' => 3,
        'akitas' => 0,
        'vizslas' => 0,
        'goldfish' => 5,
        'trees' => 3,
        'cars' => 2,
        'perfumes' => 1,
    ];

    private $aunts = [];

    protected function initialize(): void
    {
        $this->createAunts($this->getInput());
    }

    public function part1()
    {
        $ans = 0;
        foreach ($this->aunts as $key => $aunt) {
            if ($this->match($aunt)) {
                $ans = $key;
            }
        }

        return $ans;
    }

    public function part2()
    {
        $ans = 0;
        foreach ($this->aunts as $key => $aunt) {
            if ($this->match2($aunt)) {
                $ans = $key;
            }
        }

        return $ans;
    }

    private function createAunts(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    private function parseLine(string $line): void
    {
        $pattern = '/^Sue\s(?<id>\d+):\s(?<properties>.*)$/';

        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }
        $propertiesData = explode(', ', $matches['properties']);
        foreach ($propertiesData as $propertyData) {
            [$property, $number] = explode(': ', $propertyData);
            $properties[$property] = (int) $number;
        }

        $this->aunts[$matches['id']] = $properties;
    }

    private function match($aunt): bool
    {
        foreach ($aunt as $property => $number) {
            if ($this->tickerTape[$property] !== $number) {
                return false;
            }
        }

        return true;
    }

    private function match2($aunt): bool
    {
        foreach ($aunt as $property => $number) {
            if ('cats' === $property || 'trees' === $property) {
                if ($this->tickerTape[$property] >= $number) {
                    return false;
                }
            } elseif ('pomeranians' === $property || 'goldfish' === $property) {
                if ($this->tickerTape[$property] <= $number) {
                    return false;
                }
            } elseif ($this->tickerTape[$property] !== $number) {
                return false;
            }
        }

        return true;
    }
}
