<?php

namespace App\Puzzle\Year2016\Day04;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/4
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
        $ans = 0;

        foreach (explode("\n", $input->getData()) as $line) {
            $room = $this->parseLine($line);
            if ($this->check($room)) {
                $ans += $room['id'];
            }
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = 'Not found';

        foreach (explode("\n", $input->getData()) as $line) {
            $room = $this->parseLine($line);
            $realName = $this->decrypt($room);
            if (str_contains($realName, 'northpole object storage')) {
                $ans = $room['id'];
            }
        }

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function check($room): bool
    {
        $encrypt = count_chars(implode('', $room['encrypt']));

        uksort(
            $encrypt,
            function ($a, $b) use ($encrypt) {
                if ($encrypt[$a] < $encrypt[$b]) {
                    return 1;
                } elseif ($encrypt[$a] > $encrypt[$b]) {
                    return -1;
                } else {
                    return $a - $b;
                }
            }
        );

        return $room['checksum'] === implode('', array_slice(array_map('chr', array_keys($encrypt)), 0, 5));
    }

    private function decrypt($room): string
    {
        $realName = [];

        foreach ($room['encrypt'] as $encrypt) {
            foreach (str_split($encrypt) as $l) {
                $realName[] = $this->shift($l, (int) $room['id']);
            }
            $realName[] = ' ';
        }

        return trim(implode('', $realName));
    }

    private function shift(string $char, int $shift): string
    {
        $shift = $shift % 26;
        $ascii = ord($char);
        $shifted = $ascii + $shift;

        if ($shifted < 97) {
            $shifted = 123 - (97 - $shifted);
        }

        if ($shifted > 122) {
            $shifted = ($shifted - 122) + 96;
        }

        return chr($shifted);
    }

    private function parseLine(string $line): array
    {
        preg_match('/^(?<encrypt>[\w-]{0,100})-(?<id>\d+)\[(?<checksum>\w{5})\]$/', $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        $parsed['encrypt'] = explode('-', $matches['encrypt']);
        $parsed['id'] = (int) $matches['id'];
        $parsed['checksum'] = $matches['checksum'];

        return $parsed;
    }
}
