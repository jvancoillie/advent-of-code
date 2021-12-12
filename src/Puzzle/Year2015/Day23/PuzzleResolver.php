<?php

namespace App\Puzzle\Year2015\Day23;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/23
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 170;
    protected static int|string $part2Expected = 247;

    private $instructions = [];

    public function initialize(): void
    {
        $this->createInstructions($this->getInput());
    }

    public function part1()
    {
        return $this->execute(['a' => 0, 'b' => 0])['b'];
    }

    public function part2()
    {
        return $this->execute(['a' => 1, 'b' => 0])['b'];
    }

    private function createInstructions(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $exploded = explode(', ', $line);
            [$action, $register] = explode(' ', $exploded[0]);
            if ('jmp' === $action) {
                $jmp = (int) $register;
                $register = null;
            } else {
                $jmp = isset($exploded[1]) ? $exploded[1] : 0;
            }
            $this->instructions[] = ['action' => $action, 'register' => $register, 'jmp' => (int) $jmp];
        }
    }

    /**
     * @param int[] $registers
     *
     * @psalm-param array{a: 0|1, b: 0} $registers
     *
     * @return (float|int)[]
     *
     * @psalm-return array<float|int>
     */
    public function execute(array $registers): array
    {
        $i = 0;
        while ($i < count($this->instructions)) {
            $instruction = $this->instructions[$i];
            switch ($instruction['action']) {
                case 'hlf':
                    $registers[$instruction['register']] /= 2;
                    ++$i;
                    break;
                case 'tpl':
                    $registers[$instruction['register']] *= 3;
                    ++$i;
                    break;
                case 'inc':
                    $registers[$instruction['register']]++;
                    ++$i;
                    break;
                case 'jmp':
                    $i += $instruction['jmp'];
                    break;
                case 'jie':
                    if (0 === $registers[$instruction['register']] % 2) {
                        $i += $instruction['jmp'];
                    } else {
                        ++$i;
                    }
                    break;
                case 'jio':
                    if (1 === $registers[$instruction['register']]) {
                        $i += $instruction['jmp'];
                    } else {
                        ++$i;
                    }
                    break;
            }
        }

        return $registers;
    }
}
