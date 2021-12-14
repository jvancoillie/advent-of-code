<?php

namespace App\Puzzle\Year2019\Day02;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2019/day/2
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 3500;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 4714701;
    protected static int|string $part2Expected = 5121;

    private const OUTPUT = 19690720;

    public function part1(): int
    {
        $data = array_map('intval', explode(',', $this->getInput()->getData()));

        if (!$this->isTestMode()) {
            $data[1] = 12;
            $data[2] = 2;
        }

        $data = $this->processing($data);

        return (int) $data[0];
    }

    public function part2(): int
    {
        $ans = 0;
        $data = array_map('intval', explode(',', $this->getInput()->getData()));

        $length = count($data);

        for ($noun = 0; $noun < $length; ++$noun) {
            for ($verb = 0; $verb < $length; ++$verb) {
                $data[1] = $noun;
                $data[2] = $verb;

                $result = $this->processing($data);

                if (self::OUTPUT === $result[0]) {
                    $ans = 100 * $noun + $verb;
                }
            }
        }

        return $ans;
    }

    protected function processing(array $data): array
    {
        $halt = false;
        $i = 0;

        while (!$halt) {
            $opcode = $data[$i];

            switch ($opcode) {
                case 1:
                    $data[$data[$i + 3]] = $data[$data[$i + 1]] + $data[$data[$i + 2]];
                    break;
                case 2:
                    $data[$data[$i + 3]] = $data[$data[$i + 1]] * $data[$data[$i + 2]];
                    break;
                case 99:
                    $halt = true;
                    break;
            }

            $i += 4;
        }

        return $data;
    }
}
