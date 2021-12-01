<?php

namespace App\Puzzle\Year2015\Day15;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/15
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $ingredients = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createIngredients($input);
        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $ans = $this->highestScoringCookie();

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        $ans = $this->highestScoringCookie(true);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function createIngredients(PuzzleInput $input)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    private function parseLine($line)
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

    private function highestScoringCookie($withCalories = false)
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

    private function permutations($sum, $length): array
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
