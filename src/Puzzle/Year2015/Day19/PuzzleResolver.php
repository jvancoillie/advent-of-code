<?php

namespace App\Puzzle\Year2015\Day19;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/19
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $replacements = [];
    private $part2Replacements = [];
    private $input;

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->init($input);

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output): void
    {
        $ans = $this->replace(str_split($this->input));

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output): void
    {
        $ans = $this->randomSearch();

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function init(PuzzleInput $input): void
    {
        [$replacementsData, $inputData] = explode("\n\n", $input->getData());
        $this->input = $inputData;
        $this->replacements = [];
        $this->part2Replacements = [];
        foreach (explode("\n", $replacementsData) as $line) {
            [$in, $out] = explode(' => ', $line);
            $this->replacements[$out] = $in;
            $this->part2Replacements[] = [$out, $in];
        }
    }

    /**
     * @param string[] $input
     *
     * @psalm-param list<string> $input
     *
     * @psalm-return 0|positive-int
     */
    public function replace(array $input): int
    {
        $molecules = [];
        foreach ($input as $i => $l) {
            $keys = array_keys($this->replacements, $l);
            foreach ($keys as $replacement) {
                $moleculeR = $input;
                $moleculeR[$i] = $replacement;
                $molecule = implode('', $moleculeR);
                if (!in_array($molecule, $molecules)) {
                    $molecules[] = $molecule;
                }
            }
            if (isset($input[$i + 1])) {
                $two = $l.$input[$i + 1];
                $keys = array_keys($this->replacements, $two);
                foreach ($keys as $replacement) {
                    $moleculeR = $input;
                    $moleculeR[$i] = $replacement;
                    unset($moleculeR[$i + 1]);
                    $molecule = implode('', $moleculeR);
                    if (!in_array($molecule, $molecules)) {
                        $molecules[] = $molecule;
                    }
                }
            }
        }

        return count($molecules);
    }

    /**
     * @psalm-return 0|positive-int
     */
    public function randomSearch(): int
    {
        $ans = 0;
        $input = $this->input;
        while ('e' !== $input) {
            $found = false;
            foreach ($this->part2Replacements as [$search, $replacement]) {
                if ((false !== $pos = strrpos($input, $search))) {
                    $length = strlen($search);
                    $input = substr_replace($input, $replacement, $pos, $length);
                    ++$ans;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // no solution found shuffle array and restart
                shuffle($this->part2Replacements);
                $ans = 0;
                $input = $this->input;
            }
        }

        return $ans;
    }
}
