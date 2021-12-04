<?php

namespace App\Puzzle\Year2015\Day23;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/23
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $instructions = [];

    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->createInstructions($input);
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = $this->execute(['a' => 0, 'b' => 0]);

        $output->writeln('<info>Part 1 : '.$ans['b'].'</info>');
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $ans = $this->execute(['a' => 1, 'b' => 0]);

        $output->writeln('<info>Part 2 : '.$ans['b'].'</info>');
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
