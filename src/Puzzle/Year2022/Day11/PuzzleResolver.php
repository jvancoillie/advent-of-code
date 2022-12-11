<?php

namespace App\Puzzle\Year2022\Day11;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/11
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 10605;
    protected static int|string $testPart2Expected = 2713310158;

    protected static int|string $part1Expected = 90882;
    protected static int|string $part2Expected = 30893109657;

    public function part1(): int
    {
        $monkeys = $this->parse();

        return $this->monkeyBusiness($monkeys, 20, fn ($worry) => $worry / 3);
    }

    public function part2(): int
    {
        $monkeys = $this->parse();

        return $this->monkeyBusiness($monkeys, 10000, fn ($worry) => $worry % array_product(array_map(fn ($m) => $m['test']['divisibleBy'], $monkeys)));
    }

    private function monkeyBusiness($monkeys, $rounds, callable $applyReliefToWorry): int
    {
        foreach (range(1, $rounds) as $round) {
            for ($i = 0; $i < count($monkeys); ++$i) {
                while ($item = array_shift($monkeys[$i]['items'])) {
                    $worry = 0;
                    $operation = str_replace(['new', 'old'], ['$worry', '$item'], $monkeys[$i]['operation']);
                    eval($operation); // :(

                    $worry = (int) $applyReliefToWorry($worry);

                    $throw = $monkeys[$i]['test'][0 === $worry % $monkeys[$i]['test']['divisibleBy']];
                    $monkeys[$throw]['items'][] = $worry;

                    ++$monkeys[$i]['inspect'];
                }
            }
        }

        $inspect = array_map(fn ($m) => $m['inspect'], $monkeys);
        rsort($inspect);

        return $inspect[0] * $inspect[1];
    }

    private function parse(): array
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $monkeys = [];
        foreach ($data as $i => $entry) {
            $monkey = $i;
            foreach (explode("\n", $entry) as $line) {
                // test monkey number
                if (preg_match('/Monkey (.*)/', $line, $m)) {
                    continue;
                }

                // retrieve item list
                if (preg_match('/Starting items: (.*)/', trim($line), $m)) {
                    $monkeys[$monkey]['items'] = array_map('intval', explode(', ', $m[1]));
                    continue;
                }

                // the worry operation to apply on each object
                if (preg_match('/Operation: (.*)/', trim($line), $m)) {
                    $monkeys[$monkey]['operation'] = $m[1].';'; // add semicolon used by eval function
                    continue;
                }

                // the divisible by condition
                if (preg_match('/Test: divisible by (.*)/', trim($line), $m)) {
                    $monkeys[$monkey]['test']['divisibleBy'] = (int) $m[1];
                    continue;
                }
                // monkey to throw if the above condition is true
                if (preg_match('/If true: throw to monkey (.*)/', trim($line), $m)) {
                    $monkeys[$monkey]['test'][1] = (int) $m[1];
                    continue;
                }

                // monkey to throw if the above condition is false
                if (preg_match('/If false: throw to monkey (.*)/', trim($line), $m)) {
                    $monkeys[$monkey]['test'][0] = (int) $m[1];
                }
            }

            $monkeys[$i]['inspect'] = 0;
        }

        return $monkeys;
    }
}
