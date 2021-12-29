<?php

namespace App\Puzzle\Year2020\Day18;

class Calculator
{
    private string $priorityOperator = '+';
    private bool $withPriority = false;

    public function eval($line, $withPriority = false): int
    {
        $this->withPriority = $withPriority;
        $parsed = $this->parse($line);

        return $this->calculate($parsed);
    }

    public function calculate($array): int
    {
        $list = [];
        foreach ($array as $entry) {
            if (is_array($entry)) {
                $list[] = $this->calculate($entry);
            } else {
                $list[] = $entry;
            }
        }
        while (1 !== count($list)) {
            $skip = $this->hasPriority($list);
            for ($i = 0; $i < count($list); ++$i) {
                if (!is_numeric($list[$i])) {
                    $done = false;
                    $res = 0;
                    $prev = $list[$i - 1];
                    $next = $list[$i + 1];
                    if ('+' === $list[$i]) {
                        $res = $prev + $next;
                        $done = true;
                    } elseif (!$skip) {
                        $res = $prev * $next;
                        $done = true;
                    }
                    if ($done) {
                        $tmp = array_slice($list, 0, $i - 1);
                        $tmp[] = $res;
                        foreach (array_slice($list, $i + 2) as $t) {
                            $tmp[] = $t;
                        }
                        $list = $tmp;
                        break;
                    }
                }
            }
        }

        return $list[0];
    }

    public function hasPriority($list): bool
    {
        if (!$this->withPriority) {
            return false;
        }
        for ($i = 0; $i < count($list); ++$i) {
            if ($list[$i] === $this->priorityOperator) {
                return true;
            }
        }

        return false;
    }

    public function extractParenthesis($input, $index): array
    {
        $extract = [];
        for ($i = $index; $i < count($input); ++$i) {
            if ('(' === $input[$i]) {
                [$i, $sub] = $this->extractParenthesis($input, $i + 1);
                $extract[] = $sub;
            } else {
                if (')' === $input[$i]) {
                    return [$i, $extract];
                } else {
                    $extract[] = $input[$i];
                }
            }
        }

        return $extract;
    }

    public function parse($line): array
    {
        $array = str_split($line);
        $parsed = [];
        foreach ($array as $entry) {
            if (' ' == $entry) {
                continue;
            }

            if (is_numeric($entry)) {
                $parsed[] = (int) trim($entry);
            } else {
                $parsed[] = trim($entry);
            }
        }

        return $this->extractParenthesis($parsed, 0);
    }
}
