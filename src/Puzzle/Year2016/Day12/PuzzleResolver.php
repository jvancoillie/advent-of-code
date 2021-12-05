<?php

namespace App\Puzzle\Year2016\Day12;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/12
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private array $instructions = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->parseInput($input);
        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $register = $this->playInstructions([]);

        $ans = $register['a'];

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        $register = $this->playInstructions(['c' => 1]);

        $ans = $register['a'];

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    private function parseInput(PuzzleInput $input)
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
