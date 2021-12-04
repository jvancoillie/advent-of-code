<?php

namespace App\Puzzle\Year2016\Day10;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/10
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private array $bots = [];
    private array $actions = [];
    private array $outputs = [];
    private int $botComparingId;

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->parseInput($input);

        $this->playActions();

        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output): void
    {
        $ans = $this->botComparingId;

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output): void
    {
        $ans = $this->outputs['0'] * $this->outputs['1'] * $this->outputs['2'];

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function parseInput(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            if (str_starts_with($line, 'value')) {
                if (preg_match("/^value (\d+) goes to bot (\d+)/", $line, $matches)) {
                    [,$value, $bot] = $matches;
                    $this->bots[$bot][] = $value;
                }
            }

            if (str_starts_with($line, 'bot')) {
                if (preg_match("/^bot (\d+) gives low to (\w+) (\d+) and high to (\w+) (\d+)/", $line, $matches)) {
                    [, $fromBotId, $lowEntityType, $lowEntityId,$highEntityType, $highEntityId ] = $matches;
                    $this->actions[] = ['from' => $fromBotId, 'low' => ['type' => $lowEntityType, 'id' => $lowEntityId], 'high' => ['type' => $highEntityType, 'id' => $highEntityId]];
                }
            }
        }
    }

    public function playActions(): void
    {
        while ($this->actions) {
            $action = array_shift($this->actions);
            $fromBot = $this->bots[$action['from']] ?? [];
            sort($fromBot);

            if (2 !== count($fromBot)) {
                $this->actions[] = $action;
                continue;
            }

            if ($fromBot === ['17', '61']) {
                $this->botComparingId = $action['from'];
            }

            $lowValue = array_shift($fromBot);
            $highValue = array_pop($fromBot);
            $this->bots[$action['from']] = $fromBot;

            if ('bot' === $action['low']['type']) {
                $this->bots[$action['low']['id']][] = $lowValue;
            } else {
                $this->outputs[$action['low']['id']] = $lowValue;
            }
            if ('bot' === $action['high']['type']) {
                $this->bots[$action['high']['id']][] = $highValue;
            } else {
                $this->outputs[$action['high']['id']] = $highValue;
            }
        }
    }
}
