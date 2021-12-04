<?php

namespace App\Puzzle\Year2015\Day07;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $circuit = [];
    private $part1Answer;

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
        $this->createCircuit($input);
        $this->part1Answer = $this->resolveCircuitEntry('a');
        $output->writeln("<info>Part 1 : $this->part1Answer</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $this->createCircuit($input);
        $this->circuit['b'] = $this->part1Answer;
        $ans = $this->resolveCircuitEntry('a');

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    /**
     * @return (string|string[])[]
     *
     * @psalm-return array{0: non-empty-list<string>, 1: string}
     */
    public function parseLine(string $line): array
    {
        [$in, $out] = explode(' -> ', $line);
        $in = explode(' ', $in);

        return [$in, $out];
    }

    /**
     * @psalm-param 'a' $letter
     */
    public function resolveCircuitEntry(string $letter)
    {
        if (is_numeric($this->circuit[$letter])) {
            return (int) $this->circuit[$letter];
        }

        if (!is_array($this->circuit[$letter])) {
            $result = $this->resolveCircuitEntry($this->circuit[$letter]);
            $this->circuit[$letter] = $result;

            return $result;
        }

        if (3 === count($this->circuit[$letter])) {
            if (is_numeric($this->circuit[$letter][0])) {
                $a = (int) $this->circuit[$letter][0];
            } else {
                $a = $this->resolveCircuitEntry($this->circuit[$letter][0]);
            }

            $operation = $this->circuit[$letter][1];

            if (is_numeric($this->circuit[$letter][2])) {
                $b = (int) $this->circuit[$letter][2];
            } else {
                $b = $this->resolveCircuitEntry($this->circuit[$letter][2]);
            }

            $result = null;
            switch ($operation) {
                case 'AND':
                    $result = $a & $b;
                    break;
                case 'OR':
                    $result = $a | $b;
                    break;
                case 'LSHIFT':
                    $result = $a << (int) $b;
                    break;
                case 'RSHIFT':
                    $result = $a >> (int) $b;
                    break;
            }
            $this->circuit[$letter] = $result;

            return $result;
        }

        if (2 === count($this->circuit[$letter])) {
            if (is_numeric($this->circuit[$letter][1])) {
                $a = (int) $this->circuit[$letter][1];
            } else {
                $a = $this->resolveCircuitEntry($this->circuit[$letter][1]);
            }

            $result = ~(int)$a;
            $result += 65536;
            $this->circuit[$letter] = $result;

            return $result;
        }

        throw new \Exception('should never reach');
    }

    private function createCircuit(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            [$in, $out] = $this->parseLine($line);
            if (1 === count($in)) {
                $this->circuit[$out] = $in[0];
            } else {
                $this->circuit[$out] = $in;
            }
        }
    }
}
