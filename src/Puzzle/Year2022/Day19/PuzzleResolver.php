<?php

namespace App\Puzzle\Year2022\Day19;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/19
 *
 * Run out of docker need more ressources :(
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 33;
    protected static int|string $testPart2Expected = 3472;

    protected static int|string $part1Expected = 1115;
    protected static int|string $part2Expected = 25056;

    public function part1(): int
    {
        $ans = 0;
        $bluePrints = $this->parse();

        foreach ($bluePrints as $bluePrint) {
            $ans += $bluePrint['id'] * $this->maximize($bluePrint, 24);
        }

        return $ans;
    }

    public function part2(): int
    {
        $ans = 1;

        $bluePrints = array_slice($this->parse(), 0, 3);

        foreach ($bluePrints as $bluePrint) {
            $ans *= $this->maximize($bluePrint, 32);
        }

        return $ans;
    }

    private function maximize($bluePrint, $time): int
    {
        $maxOreCost = max($bluePrint['cost']['ore']['ore'], $bluePrint['cost']['clay']['ore'], $bluePrint['cost']['obsidian']['ore'], $bluePrint['cost']['geode']['ore']);
        $obsidianClayCost = $bluePrint['cost']['obsidian']['clay'];
        $geodeObsidianCost = $bluePrint['cost']['geode']['obsidian'];
        $current = ['robots' => $bluePrint['robots'], 'ressources' => $bluePrint['ressources'], 'timeLeft' => $time];
        $queue = new \SplQueue();
        $queue->enqueue($current);

        $max = 0;
        $visited = [];
        $iterations = 0;
        $skipped = 0;

        while (count($queue) > 0) {
            ++$iterations;
            $current = $queue->dequeue();

            // $this->dumpBluePrint($current);

            if (0 === $current['timeLeft']) {
                $max = max($max, $current['ressources']['geode']);
                continue;
            }

            // here prune
            $current['robots']['ore'] = min($current['robots']['ore'], $maxOreCost);
            $current['robots']['clay'] = min($current['robots']['clay'], $obsidianClayCost);
            $current['robots']['obsidian'] = min($current['robots']['obsidian'], $geodeObsidianCost);
            $current['ressources']['ore'] = min($current['ressources']['ore'], $current['timeLeft'] * $maxOreCost - $current['robots']['ore'] * ($current['timeLeft'] - 1));
            $current['ressources']['clay'] = min($current['ressources']['clay'], $current['timeLeft'] * $obsidianClayCost - $current['robots']['clay'] * ($current['timeLeft'] - 1));
            $current['ressources']['obsidian'] = min($current['ressources']['obsidian'], $current['timeLeft'] * $geodeObsidianCost - $current['robots']['obsidian'] * ($current['timeLeft'] - 1));

            $key = sprintf('%s-%s-%s', $current['timeLeft'], implode('|', $current['ressources']), implode('|', $current['robots']));

            if (isset($visited[$key])) {
                ++$skipped;
                continue;
            }

            $visited[$key] = 0;

            foreach ($this->getNextStates($current, $bluePrint['cost']) as $nextState) {
                --$nextState['timeLeft'];
                $nextState['ressources']['ore'] += $current['robots']['ore'];
                $nextState['ressources']['clay'] += $current['robots']['clay'];
                $nextState['ressources']['obsidian'] += $current['robots']['obsidian'];
                $nextState['ressources']['geode'] += $current['robots']['geode'];

                $queue->enqueue($nextState);
            }
        }

        // $this->getOutput()->writeln('Id '.$bluePrint['id'].' iterations .... '.$iterations."  skipped : $skipped max : ".$max);

        return $max;
    }

    protected function getNextStates(array $current, array $cost): iterable
    {
        // GEODE
        if ($current['ressources']['ore'] >= $cost['geode']['ore'] && $current['ressources']['obsidian'] >= $cost['geode']['obsidian']) {
            $copy = $current;
            $copy['ressources']['ore'] -= $cost['geode']['ore'];
            $copy['ressources']['obsidian'] -= $cost['geode']['obsidian'];

            ++$copy['robots']['geode'];

            yield $copy;
        }

        // OBSIDIAN
        if ($current['ressources']['ore'] >= $cost['obsidian']['ore'] && $current['ressources']['clay'] >= $cost['obsidian']['clay']) {
            $copy = $current;
            $copy['ressources']['ore'] -= $cost['obsidian']['ore'];
            $copy['ressources']['clay'] -= $cost['obsidian']['clay'];

            ++$copy['robots']['obsidian'];

            yield $copy;
        }

        // CLAY
        if ($current['ressources']['ore'] >= $cost['clay']['ore']) {
            $copy = $current;
            $copy['ressources']['ore'] -= $cost['clay']['ore'];

            ++$copy['robots']['clay'];

            yield $copy;
        }

        // ORE
        if ($current['ressources']['ore'] >= $cost['ore']['ore']) {
            $copy = $current;
            $copy['ressources']['ore'] -= $cost['ore']['ore'];

            ++$copy['robots']['ore'];

            yield $copy;
        }

        // DEFAULT
        yield $current;
    }

    private function parse(): array
    {
        $blueprints = [];
        foreach ($this->getInput()->getArrayData() as $entry) {
            preg_match('/Blueprint (?<id>.*): Each ore robot costs (?<ore>.*) ore. Each clay robot costs (?<clay>.*) ore. Each obsidian robot costs (?<obsidianOre>.*) ore and (?<obsidianClay>.*) clay. Each geode robot costs (?<geodeOre>.*) ore and (?<geodeObsidian>.*) obsidian./', $entry, $m);

            $blueprints[] = [
                'id' => (int) $m['id'],
                'cost' => [
                    'ore' => ['ore' => (int) $m['ore'], 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
                    'clay' => ['ore' => (int) $m['clay'], 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
                    'obsidian' => ['ore' => (int) $m['obsidianOre'], 'clay' => (int) $m['obsidianClay'], 'obsidian' => 0, 'geode' => 0],
                    'geode' => ['ore' => (int) $m['geodeOre'], 'clay' => 0,  'obsidian' => (int) $m['geodeObsidian'], 'geode' => 0],
                ],
                'robots' => ['ore' => 1, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
                'ressources' => ['ore' => 0, 'clay' => 0, 'obsidian' => 0, 'geode' => 0],
            ];
        }

        return $blueprints;
    }

    private function dumpBluePrint($bluePrint)
    {
        $key = sprintf('%s-%s-%s', $bluePrint['timeLeft'], implode('|', $bluePrint['ressources']), implode('|', $bluePrint['robots']));

        $this->getOutput()->writeln('=======  MINUTE '.$bluePrint['timeLeft'].' KEY '.$key.' =======');
        $this->getOutput()->writeln('  ROBOTS         => '.implode(', ', array_map(fn ($a, $b) => "$a: $b", array_keys($bluePrint['robots']), $bluePrint['robots'])));
        $this->getOutput()->writeln('  RESSOURCES     => '.implode(', ', array_map(fn ($a, $b) => "$a: $b", array_keys($bluePrint['ressources']), $bluePrint['ressources'])));
    }
}
