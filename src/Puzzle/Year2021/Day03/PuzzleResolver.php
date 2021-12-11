<?php

namespace App\Puzzle\Year2021\Day03;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 198;
    protected static int|string $testPart2Expected = 230;

    protected static int|string $part1Expected = 741950;
    protected static int|string $part2Expected = 903810;

    public function part1()
    {
        $data = $this->explodeData(explode("\n", $this->getInput()->getData()));

        $gama = $this->rateGama($data);
        $epsilon = $this->reverseBits($gama);

        return bindec($gama) * bindec($epsilon);
    }

    public function part2()
    {
        $data = $this->explodeData(explode("\n", $this->getInput()->getData()));

        $oxygen = $this->lifeRating($data);
        $co2 = $this->lifeRating($data, true);

        return bindec($oxygen) * bindec($co2);
    }

    private function rateGama(array $data): string
    {
        // transpose
        $rate = array_map(null, ...$data);

        return implode('', array_map(function ($entry) {
            return (array_sum($entry) >= count($entry) / 2) ? '1' : '0';
        }, $rate));
    }

    private function reverseBits(string $binaryString): string
    {
        return strtr($binaryString, [1, 0]);
    }

    private function lifeRating(array $data, bool $reversed = false): string
    {
        $search = 0;
        while (count($data) > 1) {
            $rate = $this->rateGama($data);

            if ($reversed) {
                $rate = $this->reverseBits($rate);
            }

            $rate = str_split($rate);

            $tmp = [];

            foreach ($data as $e) {
                if ($e[$search] == $rate[$search]) {
                    $tmp[] = $e;
                }
            }

            ++$search;

            $data = $tmp;
        }

        return implode('', $data[0]);
    }

    private function explodeData(array $data): array
    {
        return array_map(function ($e) {
            return str_split($e);
        }, $data);
    }
}
