<?php

namespace App\Puzzle\Year2015\Day06;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Grid;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $lights = Grid::create(1000, 1000, 0);

        foreach (explode("\n", $input->getData()) as $line) {
            $action = $this->parseLine($line);
            $this->applyLightInstruction($lights, $action['action'], $action['fromX'], $action['fromY'], $action['toX'], $action['toY']);
        }

        $ans = $this->countLightsLit($lights);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $lights = Grid::create(1000, 1000, 0);

        foreach (explode("\n", $input->getData()) as $line) {
            $action = $this->parseLine($line);
            $this->applyLightBrightnessInstruction($lights, $action['action'], $action['fromX'], $action['fromY'], $action['toX'], $action['toY']);
        }

        $ans = $this->countLightsLit($lights);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    /**
     * Extract action lines
     * turn on 0,0 through 999,999
     */
    public function parseLine($line)
    {
        $pattern = '/(?<action>.*)\s(?<fromX>\d+),(?<fromY>\d+)\sthrough\s(?<toX>\d+),(?<toY>\d+)/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception('parsing action error');
        }

        return $matches;
    }

    public function applyLightInstruction(&$lights, $action, $fromX, $fromY, $toX, $toY)
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

    public function applyLightBrightnessInstruction(&$lights, $action, $fromX, $fromY, $toX, $toY)
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
