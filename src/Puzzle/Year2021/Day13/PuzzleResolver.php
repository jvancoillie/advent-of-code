<?php

namespace App\Puzzle\Year2021\Day13;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 17;
    protected static int|string $testPart2Expected = 16;

    protected static int|string $part1Expected = 775;
    protected static int|string $part2Expected = 102;

    private array $dots = [];
    private array $folds = [];

    protected function initialize(): void
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $dots = explode("\n", $data[0]);
        $folds = explode("\n", $data[1]);

        foreach ($dots as $dot) {
            $this->dots[] = array_map('intval', explode(',', $dot));
        }

        foreach ($folds as $fold) {
            $fold = explode(' ', $fold);
            $this->folds[] = explode('=', $fold[2]);
        }
    }

    public function part1(): int
    {
        return count($this->fold($this->dots, reset($this->folds)));
    }

    public function part2(): int
    {
        $dots = $this->dots;

        foreach ($this->folds as $fold) {
            $dots = $this->fold($dots, $fold);
        }

        $this->dump($dots);

        return count($dots);
    }

    private function fold($dots, $fold): array
    {
        foreach ($dots as $i => [$x, $y]) {
            if ('y' === $fold[0] && $y > $fold[1]) {
                $dots[$i] = [$x, 2 * $fold[1] - $y];
                continue;
            }

            if ('x' === $fold[0] && $x > $fold[1]) {
                $dots[$i] = [2 * $fold[1] - $x, $y];
            }
        }

        return array_unique($dots, SORT_REGULAR);
    }

    public function dump($dots)
    {
        $maxX = max(array_map(fn ($dot) => $dot[0], $dots));
        $maxY = max(array_map(fn ($dot) => $dot[1], $dots));
        $lines = [];
        for ($y = 0; $y <= $maxY; ++$y) {
            $lines[$y] = '';
            for ($x = 0; $x <= $maxX; ++$x) {
                $lines[$y] .= in_array([$x, $y], $dots) ? '#' : ' ';
            }
        }
        $this->getOutput()->writeln($lines);
    }
}
