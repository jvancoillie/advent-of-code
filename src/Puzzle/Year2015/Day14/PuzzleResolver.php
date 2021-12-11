<?php

namespace App\Puzzle\Year2015\Day14;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1120;
    protected static int|string $testPart2Expected = 689;

    protected static int|string $part1Expected = 2640;
    protected static int|string $part2Expected = 1102;

    private $reindeers = [];
    private $time = 1000;

    public function main()
    {
        if ('prod' === $this->getOptions()['env']) {
            $this->time = 2503;
        }

        $this->createReindeers($this->getInput());
    }

    public function part1()
    {
        $ans = 0;
        foreach ($this->reindeers as $reindeer) {
            $dist = $reindeer['speed'] * (floor($this->time / ($reindeer['fly'] + $reindeer['rest'])) * $reindeer['fly'] + min($this->time % ($reindeer['fly'] + $reindeer['rest']), $reindeer['fly']));
            if ($dist > $ans) {
                $ans = $dist;
            }
        }

        return (int) $ans;
    }

    public function part2()
    {
        $points = [];
        for ($i = 1; $i <= $this->time; ++$i) {
            $winners = null;
            $best = 0;
            foreach ($this->reindeers as $key => $reindeer) {
                $dist = $reindeer['speed'] * (floor($i / ($reindeer['fly'] + $reindeer['rest'])) * $reindeer['fly'] + min($i % ($reindeer['fly'] + $reindeer['rest']), $reindeer['fly']));
                if ($dist > $best) {
                    $best = $dist;
                    $winners = [$key];
                } elseif ($dist === $best && $best > 0) {
                    $winners[] = $key;
                }
            }

            foreach ($winners as $winner) {
                if (isset($points[$winner])) {
                    ++$points[$winner];
                } else {
                    $points[$winner] = 1;
                }
            }
        }

        $ans = max($points);

        return $ans;
    }

    public function createReindeers(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    public function parseLine(string $line): void
    {
        $pattern = '/^(?<reindeer>.*) can fly (?<speed>\d+) km\/s for (?<fly>\d+) seconds, but then must rest for (?<rest>\d+) seconds.$/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        $this->reindeers[] = [
            'reindeer' => $matches['reindeer'],
            'speed' => $matches['speed'],
            'fly' => $matches['fly'],
            'rest' => $matches['rest'],
        ];
    }
}
