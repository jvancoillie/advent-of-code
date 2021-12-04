<?php

namespace App\Puzzle\Year2016\Day07;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/7
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
            $parsed = $this->parseLine($line);
            if (!$this->hasABBA($parsed['in']) && $this->hasABBA($parsed['out'])) {
                ++$ans;
            }
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = 0;

        foreach (explode("\n", $input->getData()) as $line) {
            $parsed = $this->parseLine($line);
            if ($this->isSSl($parsed)) {
                ++$ans;
            }
        }

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    /**
     * @return (mixed|string)[][]
     *
     * @psalm-return array{out: non-empty-list<mixed|string>, in: list<string>}
     */
    public function parseLine(string $line): array
    {
        $out = $in = [];
        while (false !== $pos = strpos($line, '[')) {
            $out[] = substr($line, 0, $pos);
            $line = substr($line, $pos + 1);
            $pos = strpos($line, ']');
            $in[] = substr($line, 0, $pos);
            $line = substr($line, $pos + 1);
        }

        $out[] = $line;

        return ['out' => $out, 'in' => $in];
    }

    public function hasABBA($strings): bool
    {
        foreach ($strings as $string) {
            $split = str_split($string);
            for ($i = 0; $i < count($split) - 3; ++$i) {
                if (
                    $split[$i] !== $split[$i + 1] &&
                    $split[$i] === $split[$i + 3] &&
                    $split[$i + 1] === $split[$i + 2]
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isSSl($ip): bool
    {
        //extract all ABA from outside brackets and check if in brackets
        foreach ($ip['out'] as $string) {
            $split = str_split($string);
            for ($i = 0; $i < count($split) - 2; ++$i) {
                if (
                    $split[$i] !== $split[$i + 1] &&
                    $split[$i] === $split[$i + 2]
                ) {
                    $bab = $split[$i + 1].$split[$i].$split[$i + 1];
                    if (array_filter($ip['in'], function ($v) use ($bab) {
                        return str_contains($v, $bab);
                    })) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
