<?php

namespace App\Puzzle\Year2016\Day12;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 42;
    protected static int|string $testPart2Expected = 42;

    protected static int|string $part1Expected = 318003;
    protected static int|string $part2Expected = 9227657;

    private array $instructions = [];

    protected function initialize(): void
    {
        $this->parseInput($this->getInput());
    }

    public function part1()
    {
        $register = $this->playInstructions([]);

        return $register['a'];
    }

    public function part2()
    {
        $register = $this->playInstructions(['c' => 1]);

        return $register['a'];
    }

    private function parseInput(PuzzleInput $input): void
    {
        $data = explode("\n", $input->getData());

        foreach ($data as $entry) {
            if (preg_match('/^(?<action>\w+) (?<data>.*)/', $entry, $matches)) {
                $this->instructions[] = ['action' => $matches['action'], 'data' => explode(' ', $matches['data'])];
            }
        }
    }

    private function playInstructions(array $register): array
    {
        $index = 0;
        $length = count($this->instructions);

        while ($index < $length) {
            $action = $this->instructions[$index]['action'];
            $data = $this->instructions[$index]['data'];
            switch ($action) {
                case 'cpy':
                    $register[$data[1]] = (ctype_alpha($data[0])) ? $register[$data[0]] : (int) $data[0];
                    ++$index;
                    break;
                case 'dec':
                    $register[$data[0]]--;
                    ++$index;
                    break;
                case 'inc':
                    $register[$data[0]]++;
                    ++$index;
                    break;
                case 'jnz':
                    $check = (ctype_alpha($data[0])) ? ($register[$data[0]] ?? 0) : (int) $data[0];
                    $index += (0 !== $check) ? $data[1] : 1;
                    break;
            }
        }

        return $register;
    }
}
