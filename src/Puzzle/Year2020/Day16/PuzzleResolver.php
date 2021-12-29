<?php

namespace App\Puzzle\Year2020\Day16;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/16
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 1;

    protected static int|string $part1Expected = 20013;
    protected static int|string $part2Expected = 5977293343129;
    private array $nearbyTickets;
    private array $myTicket;
    private array $rules;

    protected function initialize(): void
    {
        $data = explode("\n\n", $this->getInput()->getData());
        foreach (explode("\n", $data[0]) as $rule) {
            [$name, $rule] = $this->createRule($rule);
            $this->rules[$name] = $rule;
        }

        foreach (explode("\n", $data[1]) as $tickets) {
            $this->myTicket = explode(',', $tickets);
        }

        foreach (explode("\n", $data[2]) as $tickets) {
            $this->nearbyTickets[] = explode(',', $tickets);
        }
    }

    public function part1(): float|int
    {
        $invalid = [];
        foreach ($this->nearbyTickets as $ticket) {
            foreach ($ticket as $n) {
                if (!$this->isValidateByRules($n, $this->rules)) {
                    $invalid[] = $n;
                }
            }
        }

        return array_sum($invalid);
    }

    public function part2(): int
    {
        $validTickets = [];

        foreach ($this->nearbyTickets as $ticket) {
            $isValidTicket = true;
            foreach ($ticket as $n) {
                if (!$this->isValidateByRules($n, $this->rules)) {
                    $isValidTicket = false;
                    break;
                }
            }

            if ($isValidTicket) {
                $validTickets[] = $ticket;
            }
        }

        $fieldList = [];

        foreach ($this->rules as $name => $rule) {
            for ($i = 0; $i < count($this->myTicket); ++$i) {
                $validField = true;
                foreach ($validTickets as $ticket) {
                    if (!$this->isValidateByRule($ticket[$i], $rule)) {
                        $validField = false;
                        break;
                    }
                }

                if ($validField) {
                    $fieldList[$name][] = $i;
                }
            }
        }
        uasort($fieldList, fn ($a, $b) => count($a) - count($b));

        $check = [];

        foreach ($fieldList as $name => $possibles) {
            $diff = array_diff($possibles, $check);
            $fieldList[$name] = array_shift($diff);
            $check = array_merge($check, $possibles);
        }

        $t = 1;

        foreach ($fieldList as $name => $possibles) {
            if (str_starts_with($name, 'departure')) {
                $t *= $this->myTicket[$possibles];
            }
        }

        return $t;
    }

    private function createRule($str): array
    {
        [$name, $rule] = explode(': ', $str);

        $exp = explode(' or ', $rule);

        $exp = array_map(
            fn ($r) => explode('-', $r),
            $exp
        );

        return [$name, $exp];
    }

    public function isValidateByRules($number, $rules): bool
    {
        foreach ($rules as $r) {
            if ($this->isValidateByRule($number, $r)) {
                return true;
            }
        }

        return false;
    }

    private function isValidateByRule($number, $rules): bool
    {
        foreach ($rules as $rule) {
            if ($number >= $rule[0] && $number <= $rule[1]) {
                return true;
            }
        }

        return false;
    }
}
