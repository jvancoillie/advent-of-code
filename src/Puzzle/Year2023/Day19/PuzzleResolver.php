<?php

namespace App\Puzzle\Year2023\Day19;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2023/day/19
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 19114;
    protected static int|string $testPart2Expected = 167409079868000;

    protected static int|string $part1Expected = 397134;
    protected static int|string $part2Expected = 127517902575337;

    public function part1(): int
    {
        $ans = 0;

        [$rules, $parts] = array_map(fn ($e) => explode("\n", $e), explode("\n\n", $this->getInput()->getData()));

        $rules = $this->parseRules($rules);
        $parts = $this->parseParts($parts);

        foreach ($parts as $part) {
            $isAccepted = $this->isAccepted($part, $rules, 'in');
            if ($isAccepted) {
                $ans += array_sum($part);
            }
        }

        return $ans;
    }

    public function part2(): int
    {
        [$rules] = array_map(fn ($e) => explode("\n", $e), explode("\n\n", $this->getInput()->getData()));

        $rules = $this->parseRules($rules);

        return $this->countFromRules($rules, 'in', ['x' => [1, 4000], 'm' => [1, 4000], 'a' => [1, 4000], 's' => [1, 4000]]);
    }

    private function parseRules(array $rules): array
    {
        $parsed = [];

        foreach ($rules as $rule) {
            preg_match("/(?<rule>.*)\{(?<values>.*)\}/", $rule, $matches);
            $parsed[$matches['rule']] = explode(',', $matches['values']);
        }

        return $parsed;
    }

    private function parseParts(array $parts): array
    {
        return array_map(function ($part) {
            return array_reduce(
                explode(',', substr($part, 1, -1)),
                function ($a, $e) {
                    [$key, $value] = explode('=', $e);
                    $a[$key] = $value;

                    return $a;
                },
                []
            );
        }, $parts);
    }

    private function isAccepted(mixed $part, array $rules, string $workflowName)
    {
        $workflow = $rules[$workflowName];

        foreach ($workflow as $rule) {
            if ('R' === $rule) {
                return false;
            }
            if ('A' === $rule) {
                return true;
            }

            if (str_contains($rule, ':')) {
                [$condition, $result] = explode(':', $rule);
                $isValid = $this->applyCondition($condition, $part);
                if ($isValid) {
                    if ('R' === $result) {
                        return false;
                    }
                    if ('A' === $result) {
                        return true;
                    }

                    return $this->isAccepted($part, $rules, $result);
                }
                continue;
            }

            return $this->isAccepted($part, $rules, $rule);
        }
    }

    private function countFromRules(array $rules, string $workflowName, array $parts): int
    {
        $workflow = $rules[$workflowName];
        $r = [];
        foreach ($workflow as $rule) {
            if ('R' === $rule) {
                break;
            }

            if ('A' === $rule) {
                $r[] = $this->partProduct($parts);
                break;
            }

            if (str_contains($rule, ':')) {
                [$condition, $result] = explode(':', $rule);
                [$partName, $value, $sign] = $this->parseCondition($condition);

                $newParts = $parts;

                if ('>' === $sign) {
                    $newParts[$partName][0] = $value + 1;
                    $parts[$partName][1] = $value;
                } else {
                    $newParts[$partName][1] = $value - 1;
                    $parts[$partName][0] = $value;
                }

                if ('R' === $result) {
                    continue;
                }

                if ('A' === $result) {
                    $r[] = $this->partProduct($newParts);
                    continue;
                }

                $r[] = $this->countFromRules($rules, $result, $newParts);
                continue;
            }

            $r[] = $this->countFromRules($rules, $rule, $parts);
        }

        return array_sum($r);
    }

    private function applyCondition(string $condition, mixed $part): bool
    {
        [$partName, $value, $sign] = $this->parseCondition($condition);

        return '<' === $sign ? $part[$partName] < $value : $part[$partName] > $value;
    }

    public function parseCondition(string $condition): array
    {
        if (str_contains($condition, '<')) {
            [$partName, $value] = explode('<', $condition);

            return [$partName, $value, '<'];
        }

        [$partName, $value] = explode('>', $condition);

        return [$partName, $value, '>'];
    }

    private function partProduct(array $parts): int
    {
        return array_product(array_map(fn ($e) => $e[1] - $e[0] + 1, $parts));
    }
}
