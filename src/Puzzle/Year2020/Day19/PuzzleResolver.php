<?php

namespace App\Puzzle\Year2020\Day19;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/19
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 132;
    protected static int|string $part2Expected = 306;

    protected array $messages;
    protected Decoder $decoder;

    protected function initialize(): void
    {
        [$rulesInput, $messagesInput] = explode("\n\n", $this->getInput()->getData());

        $rules = [];
        foreach (explode("\n", $rulesInput) as $rule) {
            [$key, $value] = explode(':', $rule);
            if (preg_match('/\|/', $rule)) {
                $sub = [];
                foreach (explode('|', $value) as $subRule) {
                    $sub[] = explode(' ', trim($subRule));
                }
            } else {
                $sub = explode(' ', trim(str_replace('"', '', $value)));
            }
            $rules[$key] = $sub;
        }

        $this->messages = explode("\n", $messagesInput);
        $this->decoder = new Decoder($rules);
    }

    public function part1(): int
    {
        $regexp0 = $this->decoder->createRegexpFromRuleId(0);

        $sum = 0;
        foreach ($this->messages as $message) {
            if (preg_match('/^'.$regexp0.'$/', $message)) {
                ++$sum;
            }
        }

        return $sum;
    }

    public function part2(): int
    {
        $regexp42 = $this->decoder->createRegexpFromRuleId($this->isTestMode() ? 4 : 42);
        $regexp31 = $this->decoder->createRegexpFromRuleId($this->isTestMode() ? 2 : 31);

        $regexp = '/^(?<group42>('.$regexp42.')+)(?<group31>('.$regexp31.')+)$/';
        $sum = 0;

        foreach ($this->messages as $message) {
            if (preg_match($regexp, $message, $matches)) {
                if (isset($matches['group42']) && isset($matches['group31'])) {
                    preg_match_all('/'.$regexp42.'/', $matches['group42'], $matches42);
                    $matches42 = $matches42[0];
                    preg_match_all('/'.$regexp31.'/', $matches['group31'], $matches31);
                    $matches31 = $matches31[0];
                    if (count($matches42) > count($matches31)) {
                        ++$sum;
                    }
                }
            }
        }

        return $sum;
    }
}
