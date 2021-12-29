<?php

namespace App\Puzzle\Year2020\Day21;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/21
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 5;
    protected static int|string $testPart2Expected = 'mxmxvkd,sqjhc,fvjkl';

    protected static int|string $part1Expected = 2125;
    protected static int|string $part2Expected = 'phc,spnd,zmsdzh,pdt,fqqcnm,lsgqf,rjc,lzvh';

    private array $foods;
    private array $composition;

    private int $count = 0;

    protected function initialize(): void
    {
        foreach ($this->getInput()->getArrayData() as $line) {
            if (preg_match('/(?<ingredients>.*)\s\(contains\s(?<allergens>.*)\)/', $line, $matches)) {
                $this->foods[] = [explode(' ', $matches['ingredients']), explode(', ', $matches['allergens'])];
            }
        }

        $allergensList = [];
        $ingredientsList = [];
        $ingredientCount = [];

        foreach ($this->foods as [$ingredients, $allergens]) {
            // create ingredients and Allergens lists
            $ingredientsList = array_unique(array_merge($ingredientsList, $ingredients));
            $allergensList = array_unique(array_merge($allergensList, $allergens));

            // counts how many times the ingredient appears
            foreach ($ingredients as $ingredient) {
                if (isset($ingredientCount[$ingredient])) {
                    ++$ingredientCount[$ingredient];
                } else {
                    $ingredientCount[$ingredient] = 1;
                }
            }
        }

        $excluded = [];

        foreach ($this->foods as [$ingredients, $allergens]) {
            //for each ingredient on the list if it is not in the composition, the allergens can be considered to be excluded
            foreach ($ingredientsList as $ingredient) {
                if (!in_array($ingredient, $ingredients)) {
                    if (isset($excluded[$ingredient])) {
                        $excluded[$ingredient] = array_unique(array_merge($excluded[$ingredient], $allergens));
                    } else {
                        $excluded[$ingredient] = $allergens;
                    }
                }
            }
        }

        $this->count = 0;

        // for each ingredient which contains all the allergens excluded, we sum the number of times it appears in the composition
        // otherwise we create a table of possible allergen composition of the ingredient
        foreach ($excluded as $ingredient => $exclude) {
            $diff = array_diff($allergensList, $exclude);
            if (0 === count($diff)) {
                $this->count += $ingredientCount[$ingredient];
            } else {
                $this->composition[$ingredient] = $diff;
            }
        }
    }

    public function part1(): int
    {
        return $this->count;
    }

    public function part2(): string
    {
        $done = false;
        // loop as long as the composition of an ingredient is not unique
        while (!$done) {
            $done = true;
            foreach ($this->composition as $possible) {
                if (count($possible) > 1) {
                    $done = false;
                    continue;
                }
                foreach ($this->composition as $rIngredient => $rPossible) {
                    if (count($rPossible) > 1) {
                        $this->composition[$rIngredient] = array_diff($rPossible, $possible);
                    }
                }
            }
        }

        $sorted = [];
        // reverse and sort ingredient alphabetically by their allergen
        foreach ($this->composition as $ingredient => $allergens) {
            $sorted[array_shift($allergens)] = $ingredient;
        }

        ksort($sorted);

        return implode(',', $sorted);
    }
}
