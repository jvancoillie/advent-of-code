<?php

namespace App\Puzzle\Year2020\Day04;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/4
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 2;
    protected static int|string $testPart2Expected = 2;

    protected static int|string $part1Expected = 182;
    protected static int|string $part2Expected = 109;

    private array $entries = [
        'byr' => 'required',
        'iyr' => 'required',
        'eyr' => 'required',
        'hgt' => 'required',
        'hcl' => 'required',
        'ecl' => 'required',
        'pid' => 'required',
    ];

    public function part1(): int
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $passports = array_map(fn ($entry) => str_replace("\n", ' ', $entry), $data);

        $valid = 0;
        foreach ($passports as $p) {
            $lines = explode(' ', $p);
            $test = $this->entries;

            foreach ($lines as $l) {
                $key = explode(':', $l)[0];
                unset($test[$key]);
            }

            if (0 === count($test)) {
                ++$valid;
            }
        }

        return $valid;
    }

    public function part2(): int
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $passports = array_map(fn ($entry) => str_replace("\n", ' ', $entry), $data);

        $valid = 0;
        foreach ($passports as $p) {
            $lines = explode(' ', $p);
            $test = $this->entries;
            $t = true;

            foreach ($lines as $l) {
                [$key, $value] = explode(':', $l);
                if ('cid' !== $key) {
                    if (!$this->isValid($key, $value)) {
                        $t = false;
                    }
                }
                unset($test[$key]);
            }

            if (0 === count($test)) {
                if ($t) {
                    ++$valid;
                }
            }
        }

        return $valid;
    }

    private function isValid($key, $value): bool
    {
        switch ($key) {
            case 'byr':
                return $value >= 1920 && $value <= 2002;
            case 'iyr':
                return $value >= 2010 && $value <= 2020;
            case 'eyr':
                return $value >= 2020 && $value <= 2030;
            case 'hgt':
                if (preg_match('/cm$/', $value)) {
                    $h = substr($value, 0, -2);
                    if ($h >= 150 && $h <= 193) {
                        return true;
                    }
                }

                if (preg_match('/in$/', $value)) {
                    $h = substr($value, 0, -2);
                    if ($h >= 59 && $h <= 76) {
                        return true;
                    }
                }

                return false;
            case 'hcl':
                return 0 !== preg_match('/^#[a-f0-9]{6}$/', $value);
            case 'ecl':
                return in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
            case 'pid':
                return 0 !== preg_match('/^[0-9]{9}$/', $value);
        }

        return false;
    }
}
