<?php

namespace App\Puzzle\Year2015\Day06;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 998996;
    protected static int|string $testPart2Expected = 1001996;

    protected static int|string $part1Expected = 569999;
    protected static int|string $part2Expected = 17836115;

    public function part1()
    {
        $lights = Grid::create(1000, 1000, 0);

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $action = $this->parseLine($line);
            $this->applyLightInstruction($lights, $action['action'], $action['fromX'], $action['fromY'], $action['toX'], $action['toY']);
        }

        return $this->countLightsLit($lights);
    }

    public function part2()
    {
        $lights = Grid::create(1000, 1000, 0);

        foreach (explode("\n", $this->getInput()->getData()) as $line) {
            $action = $this->parseLine($line);
            $this->applyLightBrightnessInstruction($lights, $action['action'], $action['fromX'], $action['fromY'], $action['toX'], $action['toY']);
        }

        return $this->countLightsLit($lights);
    }

    /**
     * Extract action lines
     * turn on 0,0 through 999,999.
     *
     * @return string[]
     *
     * @psalm-return array<string>
     */
    public function parseLine(string $line): array
    {
        $pattern = '/(?<action>.*)\s(?<fromX>\d+),(?<fromY>\d+)\sthrough\s(?<toX>\d+),(?<toY>\d+)/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        return $matches;
    }

    /**
     * @param array[] $lights
     *
     * @psalm-param array<0|positive-int, array<0|positive-int, mixed>> $lights
     */
    public function applyLightInstruction(array &$lights, string $action, string $fromX, string $fromY, string $toX, string $toY): void
    {
        for ($y = $fromY; $y <= $toY; ++$y) {
            for ($x = $fromX; $x <= $toX; ++$x) {
                switch ($action) {
                    case 'turn on':
                        $lights[$y][$x] = 1;
                        break;
                    case 'turn off':
                        $lights[$y][$x] = 0;
                        break;
                    case 'toggle':
                        $lights[$y][$x] = (1 === $lights[$y][$x]) ? 0 : 1;
                        break;
                }
            }
        }
    }

    /**
     * @param array[] $lights
     *
     * @psalm-param array<0|positive-int, array<0|positive-int, mixed>> $lights
     */
    public function applyLightBrightnessInstruction(array &$lights, string $action, string $fromX, string $fromY, string $toX, string $toY): void
    {
        for ($y = $fromY; $y <= $toY; ++$y) {
            for ($x = $fromX; $x <= $toX; ++$x) {
                switch ($action) {
                    case 'turn on':
                        $lights[$y][$x]++;
                        break;
                    case 'turn off':
                        $lights[$y][$x]--;
                        if ($lights[$y][$x] < 0) {
                            $lights[$y][$x] = 0;
                        }
                        break;
                    case 'toggle':
                        $lights[$y][$x] += 2;
                        break;
                }
            }
        }
    }

    public function countLightsLit($lights)
    {
        $lit = 0;
        foreach ($lights as $line) {
            foreach ($line as $light) {
                $lit += $light;
            }
        }

        return $lit;
    }
}
