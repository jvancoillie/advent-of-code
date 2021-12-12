<?php

namespace App\Puzzle\Year2016\Day01;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/1
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 8;
    protected static int|string $testPart2Expected = 4;

    protected static int|string $part1Expected = 300;
    protected static int|string $part2Expected = 159;

    private $instructions = [];
    private $currentDirection = 'N';
    private $directions = ['N', 'E', 'S', 'W'];

    public function initialize(): void
    {
        $this->createInstructions();
    }

    public function part1()
    {
        [$x, $y] = $this->walk();

        return $this->manhattan(0, 0, $x, $y);
    }

    public function part2()
    {
        [$x, $y] = $this->walk(true);

        return $this->manhattan(0, 0, $x, $y);
    }

    private function createInstructions(): void
    {
        foreach (explode(', ', $this->getInput()->getData()) as $line) {
            $this->instructions[] = ['turn' => substr($line, 0, 1), 'dist' => (int) substr($line, 1)];
        }
    }

    /**
     * @return int[]
     *
     * @psalm-return array{0: int, 1: int}
     */
    private function walk(bool $firstVisited = false): array
    {
        $x = $y = 0;
        $this->currentDirection = 'N';
        $visited = [];
        foreach ($this->instructions as $instruction) {
            $this->turn($instruction['turn']);
            $targets = [];
            switch ($this->currentDirection) {
                case 'N':
                    for ($i = 0; $i < $instruction['dist']; ++$i) {
                        --$y;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'S':
                    for ($i = 0; $i < $instruction['dist']; ++$i) {
                        ++$y;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'E':
                    for ($i = 0; $i < $instruction['dist']; ++$i) {
                        ++$x;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
                case 'W':
                    for ($i = 0; $i < $instruction['dist']; ++$i) {
                        --$x;
                        $targets[] = ['x' => $x, 'y' => $y];
                    }
                    break;
            }
            if ($firstVisited) {
                foreach ($targets as $target) {
                    $check = $target['x'].'-'.$target['y'];
                    if (in_array($check, $visited)) {
                        return [$target['x'], $target['y']];
                    }
                    $visited[] = $check;
                }
            }
        }

        return [$x, $y];
    }

    private function manhattan(int $xa, int $ya, $xb, $yb): float|int
    {
        return abs($ya - $yb) + abs($xa - $xb);
    }

    private function turn($direction): void
    {
        $key = array_search($this->currentDirection, $this->directions);

        if ('R' === $direction) {
            ++$key;
        } else {
            --$key;
        }

        if ($key < 0) {
            $key = count($this->directions) - 1;
        }
        $key %= (count($this->directions));

        $this->currentDirection = $this->directions[$key];
    }
}
