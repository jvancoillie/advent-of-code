<?php

namespace App\Puzzle\Year2024\Day13;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2024/day/13
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 480;
    protected static int|string $testPart2Expected = 875318608908;

    protected static int|string $part1Expected = 28262;
    protected static int|string $part2Expected = 101406661266314;

    public function part1(): int
    {
        $ans = 0;

        $data = array_map(fn (string $input) => explode("\n", $input), explode("\n\n", $this->getInput()->getData()));

        $entries = $this->extract($data);

        foreach ($entries as $entry) {
            try {
                [$a, $b] = $this->solveEquations($entry['A'][0], $entry['B'][0], $entry['Prize'][0], $entry['A'][1], $entry['B'][1], $entry['Prize'][1]);
            } catch (\Exception $e) {
                continue;
            }

            $cost = $a * 3 + $b;
            $ans += $cost;
            // dump("Solution: A = $a, B = $b cost $cost");
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 0;

        $data = array_map(fn (string $input) => explode("\n", $input), explode("\n\n", $this->getInput()->getData()));

        $entries = $this->extract($data);

        foreach ($entries as $entry) {
            try {
                [$a, $b] = $this->solveEquations($entry['A'][0], $entry['B'][0], $entry['Prize'][0], $entry['A'][1], $entry['B'][1], $entry['Prize'][1], true);
            } catch (\Exception $e) {
                continue;
            }

            $cost = $a * 3 + $b;
            $ans += $cost;
            //            dump("Solution: A = $a, B = $b cost $cost");
        }

        return $ans;
    }

    private function extract(array $datas): array
    {
        $entries = [];
        foreach ($datas as $data) {
            $entry = [];
            if (preg_match('/Button A: X\+(\d+), Y\+(\d+)/', $data[0], $matches)) {
                $entry['A'] = [(int) $matches[1], (int) $matches[2]];
            }
            if (preg_match('/Button B: X\+(\d+), Y\+(\d+)/', $data[1], $matches)) {
                $entry['B'] = [(int) $matches[1], (int) $matches[2]];
            }
            if (preg_match('/Prize: X=(\d+), Y=(\d+)/', $data[2], $matches)) {
                $entry['Prize'] = [(int) $matches[1], (int) $matches[2]];
            }
            $entries[] = $entry;
        }

        return $entries;
    }

    private function solveEquations(int $aX, int $bX, int $targetX, int $aY, int $bY, int $targetY, bool $conversion = false): array
    {
        if ($conversion) {
            $targetX += 10000000000000;
            $targetY += 10000000000000;
        }

        $det = $aX * $bY - $bX * $aY;

        if (0 === $det) {
            throw new \RuntimeException('The system of equations has no unique solution.');
        }

        $a = ($targetX * $bY - $targetY * $bX) / $det;
        $b = ($aX * $targetY - $aY * $targetX) / $det;

        if (!is_int($a) || !is_int($b)) {
            throw new \RuntimeException('No integer solution found.');
        }

        return [(int) $a, (int) $b];
    }
}
