<?php

namespace App\Puzzle\Year2020\Day25;

use App\Puzzle\AbstractPuzzleResolver;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int $testPart1Expected = 14897079;
    protected static int $testPart2Expected = 0;

    protected static int $part1Expected = 12929;
    protected static int $part2Expected = 0;

    public function part1()
    {
        $data = explode("\n", $this->getInput()->getData());

        $cardSubjectNumber = (int) $data[0];
        $doorSubjectNumber = (int) $data[1];

        $doorLoopSize = $this->findLoopSize($doorSubjectNumber);
        $cardKey = $this->transformNumber($cardSubjectNumber, $doorLoopSize);

        return $cardKey;
    }

    public function part2()
    {
        return 0;
    }

    /**
     * @psalm-return 0|positive-int
     */
    public function findLoopSize(int $key): int
    {
        $i = 0;
        $subjectNumber = 7;
        $value = 1;

        while ($key !== $value) {
            ++$i;
            $value *= $subjectNumber;
            $value %= 20201227;
        }

        return $i;
    }

    /**
     * @psalm-return int<-20201226, 20201226>
     * @psalm-param 0|positive-int $loopSize
     */
    public function transformNumber(int $subjectNumber, int $loopSize): int
    {
        $n = $subjectNumber;
        $value = 1;
        for ($i = 0; $i < $loopSize; ++$i) {
            $value = $value * $n;
            $value = $value % 20201227;
        }

        return $value;
    }
}
