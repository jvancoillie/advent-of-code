<?php

namespace App\Puzzle\Year2015\Day15;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 62842880;
    protected static int|string $testPart2Expected = 57600000;

    protected static int|string $part1Expected = 222870;
    protected static int|string $part2Expected = 117936;

    private $ingredients = [];

    /**
     * @return void
     */
    public function main()
    {
        $this->createIngredients($this->getInput());
    }

    public function part1()
    {
        return $this->highestScoringCookie();
    }

    public function part2()
    {
        return $this->highestScoringCookie(true);
    }

    private function createIngredients(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    private function parseLine(string $line): void
    {
        [$ingredient, $properties] = explode(': ', $line);
        $propertiesData = explode(', ', $properties);
        $properties = ['ingredient' => $ingredient];
        $value = 0;
        foreach ($propertiesData as $propertyData) {
            [$property, $teaspoon] = explode(' ', $propertyData);
            $properties[$property] = (int) $teaspoon;
            if ('calories' !== $property) {
                $value += $properties[$property];
            }
        }

        $this->ingredients[] = $properties;
    }

    private function highestScoringCookie(bool $withCalories = false)
    {
        $highestScore = 0;

        $permutations = $this->permutations(100, count($this->ingredients) - 1);
        foreach ($permutations as $multiplier) {
            $capacity = $durability = $flavor = $texture = $calories = 0;
            foreach ($this->ingredients as $key => $ingredient) {
                $capacity += $ingredient['capacity'] * $multiplier[$key];
                $durability += $ingredient['durability'] * $multiplier[$key];
                $flavor += $ingredient['flavor'] * $multiplier[$key];
                $texture += $ingredient['texture'] * $multiplier[$key];
                $calories += $ingredient['calories'] * $multiplier[$key];
            }

            if ($withCalories && 500 != $calories) {
                continue;
            }

            $capacity = max(0, $capacity);
            $durability = max(0, $durability);
            $flavor = max(0, $flavor);
            $texture = max(0, $texture);

            $highestScore = max($highestScore, $capacity * $durability * $flavor * $texture);
        }

        return $highestScore;
    }

    /**
     * @psalm-param 100 $sum
     */
    private function permutations(int $sum, int $length): array
    {
        if (0 === $length) {
            return [[$sum]];
        }

        $list = [];

        for ($i = $sum; $i > 0; --$i) {
            $subList = $this->permutations($sum - $i, $length - 1);
            foreach ($subList as $perms) {
                $perms[] = $i;
                $list[] = $perms;
            }
        }

        return $list;
    }
}
