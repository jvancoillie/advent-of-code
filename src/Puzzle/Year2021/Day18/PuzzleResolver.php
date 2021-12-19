<?php

namespace App\Puzzle\Year2021\Day18;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/18
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 4140;
    protected static int|string $testPart2Expected = 3993;

    protected static int|string $part1Expected = 4008;
    protected static int|string $part2Expected = 4667;
    protected string|null $action = null;

    public function part1(): float|int
    {
        $data = explode("\n", $this->getInput()->getData());
        $snailfish = array_shift($data);
        while (!empty($data)) {
            $add = array_shift($data);

            $snailfish = $this->add($snailfish, $add);
            $snailfish = $this->reduce($snailfish);
        }
        $exploded = $this->explodeSnailfish($snailfish);

        return $this->magnitude($exploded);
    }

    public function part2()
    {
        $max = 0;

        $data = explode("\n", $this->getInput()->getData());
        foreach ($data as $snailfish1) {
            foreach ($data as $snailfish2) {
                if ($snailfish1 === $snailfish2) {
                    continue;
                }
                $snailfish = $this->add($snailfish1, $snailfish2);
                $snailfish = $this->reduce($snailfish);

                $exploded = $this->explodeSnailfish($snailfish);
                $magnitude = $this->magnitude($exploded);
                $max = max($magnitude, $max);
            }
        }

        return $max;
    }

    public function reduceExplode(array $pairs, $depth = 0)
    {
        ++$depth;

        // explode ....
        if (empty($this->action)) {
            if ($depth > 3) {
                if (is_array($pairs[0])) {
                    $this->action = 'explode part 1';
                    $t = is_array($pairs[1]) ? $this->addLeft($pairs[1], $pairs[0][1]) : ($pairs[1] + $pairs[0][1]);

                    return ['to' => 'left', 'result' => [0, $t], 'remaining' => $pairs[0][0]];
                }

                if (is_array($pairs[1])) {
                    $this->action = 'explode part 2';
                    $t = $pairs[0] + $pairs[1][0];

                    return ['to' => 'right', 'result' => [$t, 0], 'remaining' => $pairs[1][1]];
                }
            }
        }

        $reduce1 = is_array($pairs[0]) ? $this->reduceExplode($pairs[0], $depth) : $pairs[0];
        $reduce2 = is_array($pairs[1]) ? $this->reduceExplode($pairs[1], $depth) : $pairs[1];

        $pairs[0] = $reduce1;
        $pairs[1] = $reduce2;
        $backward = [];

        if (isset($reduce1['to'])) {
            $reduce1['from'] = 'left';
            $pairs[0] = $reduce1['result'];
            $backward = $reduce1;
        }

        if (isset($reduce2['to'])) {
            $reduce2['from'] = 'right';
            $pairs[1] = $reduce2['result'];
            $backward = $reduce2;
        }

        if (isset($backward['from']) && $backward['to'] !== $backward['from']) {
            if ('right' === $backward['to']) {
                if (is_array($pairs[1])) {
                    $pairs[1] = $this->addLeft($pairs[1], $backward['remaining']);
                } else {
                    $pairs[1] += $backward['remaining'];
                }
            } elseif ('left' === $backward['to']) {
                if (is_array($pairs[0])) {
                    $pairs[0] = $this->addRight($pairs[0], $backward['remaining']);
                } else {
                    $pairs[0] += $backward['remaining'];
                }
            }

            return $pairs;
        }

        if ($backward && $depth > 1) {
            $backward['result'] = $pairs;

            return $backward;
        }

        return $pairs;
    }

    public function reduceSplit(array $pairs): array
    {
        // explode ....
        $pairs[0] = is_array($pairs[0]) ? $this->reduceSplit($pairs[0]) : $pairs[0];
        if (empty($this->action)) {
            // split ....
            if (!is_array($pairs[0]) && $pairs[0] > 9) {
                $this->action = 'split';
                $pairs[0] = [floor($pairs[0] / 2), ceil($pairs[0] / 2)];

                return $pairs;
            }
        }

        $pairs[1] = is_array($pairs[1]) ? $this->reduceSplit($pairs[1]) : $pairs[1];
        if (empty($this->action)) {
            // split ....
            if (!is_array($pairs[0]) && $pairs[0] > 9) {
                $this->action = 'split';
                $pairs[0] = [floor($pairs[0] / 2), ceil($pairs[0] / 2)];

                return $pairs;
            }

            if (!is_array($pairs[1]) && $pairs[1] > 9) {
                $this->action = 'split';
                $pairs[1] = [floor($pairs[1] / 2), ceil($pairs[1] / 2)];

                return $pairs;
            }
        }

        return $pairs;
    }

    public function addLeft($array, $add)
    {
        if (is_array($array[0])) {
            $array[0] = $this->addLeft($array[0], $add);
        } else {
            $array[0] += $add;
        }

        return $array;
    }

    public function addRight($array, $add)
    {
        if (is_array($array[1])) {
            $array[1] = $this->addRight($array[1], $add);
        } else {
            $array[1] += $add;
        }

        return $array;
    }

    private function explodeSnailfish(string $snailfish): array
    {
        return json_decode($snailfish);
    }

    private function implodeSnailfish(array $pairs): string
    {
        return json_encode($pairs);
    }

    private function add($a, $b): string
    {
        return sprintf('[%s,%s]', $a, $b);
    }

    private function magnitude(array $array): float|int
    {
        if (is_array($array[0])) {
            $array[0] = $this->magnitude($array[0]);
        }

        if (is_array($array[1])) {
            $array[1] = $this->magnitude($array[1]);
        }

        return 3 * $array[0] + 2 * $array[1];
    }

    protected function reduce(string $snailfish): string
    {
        $this->action = null;
        $exploded = $this->explodeSnailfish($snailfish);
        // reduce exploded
        $exploded = $this->reduceExplode($exploded);

        if (!empty($this->action)) {
            return $this->reduce($this->implodeSnailfish($exploded));
        }

        $exploded = $this->reduceSplit($exploded);

        if (!empty($this->action)) {
            return $this->reduce($this->implodeSnailfish($exploded));
        }

        return $this->implodeSnailfish($exploded);
    }
}
