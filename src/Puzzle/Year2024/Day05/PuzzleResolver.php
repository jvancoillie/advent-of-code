<?php

namespace App\Puzzle\Year2024\Day05;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/5
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 143;
    protected static int|string $testPart2Expected = 123;

    protected static int|string $part1Expected = 6034;
    protected static int|string $part2Expected = 6305;

    public function part1(): int
    {
        return $this->solve(fn ($section, $rules) => $this->isValidSection($section, $rules));
    }

    public function part2(): int
    {
        return $this->solve(fn ($section, $rules) => !$this->isValidSection($section, $rules), true);
    }

    private function solve(callable $condition, bool $reorder = false): int
    {
        $ans = 0;
        [$rules, $sections] = $this->parseInput($this->getInput()->getData());

        foreach ($sections as $section) {
            if ($condition($section, $rules)) {
                if ($reorder) {
                    $section = $this->reorder($section, $rules);
                }
                $ans += (int) $section[floor(count($section) / 2)];
            }
        }

        return $ans;
    }

    private function parseInput(string $input): array
    {
        [$a, $b] = explode("\n\n", $input);
        $rules = [];
        foreach (explode("\n", $a) as $rule) {
            [$l, $r] = explode('|', $rule);
            $rules[$l][] = $r;
        }

        $sections = array_map(fn ($line) => explode(',', $line), explode("\n", $b));

        return [$rules, $sections];
    }

    private function isValidSection(array $section, array $rules): bool
    {
        $visited = [];

        foreach ($section as $page) {
            $visited[] = $page;
            if (!isset($rules[$page])) {
                continue;
            }

            foreach ($rules[$page] as $after) {
                if (in_array($after, $visited)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function reorder(array $section, array $rules): array
    {
        $priority = [];
        foreach ($rules as $key => $values) {
            foreach ($values as $value) {
                $priority[$value][] = $key;
            }
        }

        usort($section, function ($a, $b) use ($priority) {
            if (!isset($priority[$a]) && !isset($priority[$b])) {
                return 0;
            }
            if (!isset($priority[$a])) {
                return -1;
            }
            if (!isset($priority[$b])) {
                return 1;
            }
            if (in_array($a, $priority[$b])) {
                return -1;
            }
            if (in_array($b, $priority[$a])) {
                return 1;
            }

            return 0;
        });

        return $section;
    }
}
