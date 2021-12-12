<?php

namespace App\Puzzle\Year2015\Day07;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;

/**
 * Class PuzzleResolver.
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 123;
    protected static int|string $testPart2Expected = 123;

    protected static int|string $part1Expected = 956;
    protected static int|string $part2Expected = 40149;

    private $circuit = [];
    private $part1Answer;
    private $wire;

    protected function initialize(): void
    {
        $this->wire = 'test' === $this->getOptions()['env'] ? 'x' : 'a';
        $this->createCircuit($this->getInput());

        $this->part1Answer = $this->resolveCircuitEntry($this->wire);
    }

    public function part1()
    {
        return $this->part1Answer;
    }

    public function part2()
    {
        $this->createCircuit($this->getInput());
        $this->circuit['b'] = $this->part1Answer;

        return $this->resolveCircuitEntry($this->wire);
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

            $result = ~(int) $a;
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
