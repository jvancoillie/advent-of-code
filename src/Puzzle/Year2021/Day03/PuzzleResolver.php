<?php

namespace App\Puzzle\Year2021\Day03;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $data = $this->explodeData(explode("\n", $input->getData()));

        $gama = $this->rateGama($data);
        $epsilon = $this->reverseBits($gama);

        $ans = bindec($gama) * bindec($epsilon);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $data = $this->explodeData(explode("\n", $input->getData()));

        $oxygen = $this->lifeRating($data);
        $co2 = $this->lifeRating($data, true);

        $ans = bindec($oxygen) * bindec($co2);

        $output->writeln("<info>Part 2 : $ans</info>");
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
