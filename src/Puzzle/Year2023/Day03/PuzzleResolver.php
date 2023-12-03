<?php

namespace App\Puzzle\Year2023\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4361;
    protected static int|string $testPart2Expected = 467835;

    protected static int|string $part1Expected = 546312;
    protected static int|string $part2Expected = 87449461;

    public const DIRECTIONS = [
        [-1, -1], [-1, 0], [-1, 1],
        [0, -1],             [0, 1],
        [1, -1], [1, 0], [1, 1],
    ];

    private array $validNumbers = [];
    private array $gears = [];

    protected function initialize(): void
    {
        $grid = $this->parse($this->getInput()->getArrayData());

        $number = '';
        $symbolCoordinates = [];

        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                if (is_numeric($cell)) {
                    $number .= $cell;
                    $symbolCoordinates = $this->getSymbol($x, $y, $grid) ?: $symbolCoordinates;

                    if ($y < count($row) - 1) {
                        continue;
                    }
                }

                if (!empty($number) && $symbolCoordinates) {
                    $this->validNumbers[] = (int) $number;

                    if ($this->isGear($symbolCoordinates, $grid)) {
                        $gearIndex = implode('|', $symbolCoordinates);
                        $this->gears[$gearIndex][] = (int) $number;
                    }
                }

                $symbolCoordinates = [];
                $number = '';
            }
        }
    }

    public function part1(): int
    {
        return array_sum($this->validNumbers);
    }

    public function part2(): int
    {
        return array_reduce(
            array_filter(
                $this->gears,
                fn ($numbers) => count($numbers) > 1
            ),
            fn ($carry, $numbers) => $carry + array_product($numbers));
    }

    private function parse(array $data): array
    {
        return array_map(fn ($line) => str_split($line), $data);
    }

    private function getSymbol(int $x, int $y, array $grid): array
    {
        foreach (self::DIRECTIONS as [$dx, $dy]) {
            if (!isset($grid[$x + $dx][$y + $dy])) {
                continue;
            }

            if ('.' === $grid[$x + $dx][$y + $dy]) {
                continue;
            }

            if (is_numeric($grid[$x + $dx][$y + $dy])) {
                continue;
            }

            return [$x + $dx, $y + $dy];
        }

        return [];
    }

    private function isGear(array $coordinates, $grid): bool
    {
        [$x, $y] = $coordinates;

        return '*' === $grid[$x][$y];
    }
}
