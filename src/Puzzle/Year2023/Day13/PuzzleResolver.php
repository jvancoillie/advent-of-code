<?php

namespace App\Puzzle\Year2023\Day13;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 405;
    protected static int|string $testPart2Expected = 400;

    protected static int|string $part1Expected = 31956;
    protected static int|string $part2Expected = 37617;

    public function part1(): int
    {
        $ans = 0;

        $patterns = explode("\n\n", $this->getInput()->getData());

        foreach ($patterns as $pattern) {
            $data = explode("\n", $pattern);
            $reflectionAt = $this->getLineReflexion($data);

            if (-1 !== $reflectionAt) {
                $ans += 100 * $reflectionAt;
                continue;
            }

            $data = $this->rotate($data);
            $reflectionAt = $this->getLineReflexion($data);
            $ans += $reflectionAt;
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $patterns = explode("\n\n", $this->getInput()->getData());

        foreach ($patterns as $pattern) {
            $data = explode("\n", $pattern);
            $reflectionAt = $this->getLineReflexion($data, 1);

            if (-1 !== $reflectionAt) {
                $ans += 100 * $reflectionAt;
                continue;
            }

            $data = $this->rotate($data);
            $reflectionAt = $this->getLineReflexion($data, 1);
            $ans += $reflectionAt;
        }

        return $ans;
    }

    private function getLineReflexion(array $data, $delta = 0): int
    {
        for ($i = 0; $i < count($data) - 1; ++$i) {
            $top = $i;
            $bottom = $i + 1;
            $diff = 0;
            while ($top >= 0 && $bottom < count($data)) {
                if ($data[$top] !== $data[$bottom]) {
                    for ($c = 0; $c < strlen($data[$top]); ++$c) {
                        if ($data[$top][$c] === $data[$bottom][$c]) {
                            continue;
                        }
                        ++$diff;
                    }
                }

                --$top;
                ++$bottom;
            }

            if ($diff === $delta) {
                return $i + 1;
            }
        }

        return -1;
    }

    private function rotate(array $data): array
    {
        return array_map('join', Grid::rotate(array_map('str_split', $data)));
    }
}
