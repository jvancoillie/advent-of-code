<?php

namespace App\Puzzle\Year2020\Day19;

class Decoder
{
    public function __construct(private $rules)
    {
    }

    public function createRegexpFromRuleId($id): string
    {
        return $this->createRegexp($this->rules[$id], $this->rules);
    }

    public function createRegexp($rule, $rules): string
    {
        if (ctype_alpha($rule[0])) {
            return $rule[0];
        }
        $chain = [];
        if (is_array($rule[0])) {
            // here got piped need to go deep
            $chain[] = $this->createRegexp($rule[0], $rules);
            $chain[] = $this->createRegexp($rule[1], $rules);

            return '('.implode('|', $chain).')';
        } else {
            foreach ($rule as $n) {
                $chain[] = $this->createRegexp($rules[$n], $rules);
            }

            return implode('', $chain);
        }
    }
}
