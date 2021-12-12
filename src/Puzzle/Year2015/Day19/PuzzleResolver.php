<?php

namespace App\Puzzle\Year2015\Day19;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/19
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 7;
    protected static int|string $testPart2Expected = 6;

    protected static int|string $part1Expected = 509;
    protected static int|string $part2Expected = 195;

    private $replacements = [];
    private $part2Replacements = [];
    private $input;

    public function initialize(): void
    {
        $this->init($this->getInput());
    }

    public function part1()
    {
        return $this->replace(str_split($this->input));
    }

    public function part2()
    {
        return $this->randomSearch();
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
