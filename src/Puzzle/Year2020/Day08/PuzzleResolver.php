<?php

namespace App\Puzzle\Year2020\Day08;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/8
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 1684;
    protected static int|string $part2Expected = 2188;

    private array $instructions;

    public function initialize(): void
    {
        $this->instructions = array_map(function ($line) {
            return explode(' ', $line);
        }, $this->getInput()->getArrayData());
    }

    public function part1(): int
    {
        $accumulator = 0;
        $changed = $this->instructions;

        if ('nop' === $this->instructions[0][0]) {
            $changed[0] = ['jmp', $this->instructions[0][1]];
        }
        if ('jmp' === $this->instructions[0][0]) {
            $changed[0] = ['nop', $this->instructions[0][1]];
        }

        $this->execute($changed, $accumulator);

        return $accumulator;
    }

    public function part2(): int
    {
        $accumulator = 0;

        foreach ($this->instructions as $key => $action) {
            $changed = $this->instructions;

            if ('nop' === $action[0]) {
                $changed[$key] = ['jmp', $action[1]];
            }

            if ('jmp' === $action[0]) {
                $changed[$key] = ['nop', $action[1]];
            }

            $accumulator = 0;
            if ($this->execute($changed, $accumulator)) {
                break;
            }
        }

        return $accumulator;
    }

    private function execute($commands, &$accumulator): bool
    {
        $isDone = true;
        $done = [];
        for ($i = 0; $i < count($commands); ++$i) {
            if (in_array($i, $done)) {
                $isDone = false;
                break;
            }

            $done[] = $i;
            $action = $commands[$i][0];
            $nb = (int) substr($commands[$i][1], 1);
            $sign = substr($commands[$i][1], 0, 1);
            switch ($action) {
                case 'acc':
                    if ('+' === $sign) {
                        $accumulator += $nb;
                    } else {
                        $accumulator -= $nb;
                    }
                    break;
                case 'jmp':
                    if ('+' === $sign) {
                        $i += ($nb - 1);
                    } else {
                        $i -= ($nb + 1);
                    }
                    break;
            }
        }

        return $isDone;
    }
}
