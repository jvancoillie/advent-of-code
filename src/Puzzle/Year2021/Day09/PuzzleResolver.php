<?php

namespace App\Puzzle\Year2021\Day09;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private array $grid = [];
    private array $directions = [
        [0, 1],
        [0, -1],
        [-1, 0],
        [1, 0],
    ];
    private array $visited = [];
    private array $basinLengths = [];
    private array $lowPoints = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = []): void
    {
        $this->createGrid(explode("\n", $input->getData()));

        $this->browse();

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output): void
    {
        $ans = array_sum($this->lowPoints) + count($this->lowPoints);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output): void
    {
        $ans = array_product(array_slice($this->basinLengths, 0, 3));

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function createGrid($data): void
    {
        foreach ($data as $line) {
            $this->grid[] = array_map('intval', str_split($line));
        }
    }

    public function browse(): void
    {
        for ($y = 0; $y < count($this->grid); ++$y) {
            for ($x = 0; $x < count($this->grid[$y]); ++$x) {
                $currentValue = $this->grid[$y][$x];
                $isLowest = true;
                foreach ($this->directions as [$dy, $dx]) {
                    $nx = $dx + $x;
                    $ny = $dy + $y;

                    if (isset($this->grid[$ny][$nx]) && $this->grid[$ny][$nx] <= $currentValue) {
                        $isLowest = false;
                        break;
                    }
                }

                if ($isLowest) {
                    $this->visited = [];
                    $this->basinLengths[] = count($this->getbasin($x, $y));
                    $this->lowPoints[] = $currentValue;
                }
            }
        }
        rsort($this->basinLengths);
    }

    private function getBasin($x, $y, $done = []): array
    {
        $r = [$this->grid[$y][$x]];
        $this->visited[] = "$x-$y";

        foreach ($this->directions as [$dy, $dx]) {
            $nx = $dx + $x;
            $ny = $dy + $y;

            if (isset($this->grid[$ny][$nx]) && !in_array("$nx-$ny", $this->visited) && 9 !== $this->grid[$ny][$nx]) {
                $r = array_merge($r, $this->getBasin($nx, $ny, $done));
            }
        }

        return $r;
    }
}
