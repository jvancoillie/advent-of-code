<?php

namespace App\Puzzle\Year2015\Day21;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use App\Utils\Generator;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/21
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 65;
    protected static int|string $testPart2Expected = 188;

    protected static int|string $part1Expected = 78;
    protected static int|string $part2Expected = 148;

    private array $shop = [
        'Weapons' => [
            'Dagger' => ['Cost' => 8,   'Damage' => 4, 'Armor' => 0],
            'Shortsword' => ['Cost' => 10,  'Damage' => 5, 'Armor' => 0],
            'Warhammer' => ['Cost' => 25,  'Damage' => 6, 'Armor' => 0],
            'Longsword' => ['Cost' => 40,  'Damage' => 7, 'Armor' => 0],
            'Greataxe' => ['Cost' => 74,  'Damage' => 8, 'Armor' => 0],
        ],
        'Armor' => [
            'Leather' => ['Cost' => 13,  'Damage' => 0, 'Armor' => 1],
            'Chainmail' => ['Cost' => 31,  'Damage' => 0, 'Armor' => 2],
            'Splintmail' => ['Cost' => 53,  'Damage' => 0, 'Armor' => 3],
            'Bandedmail' => ['Cost' => 75,  'Damage' => 0, 'Armor' => 4],
            'Platemail' => ['Cost' => 102, 'Damage' => 0, 'Armor' => 5],
        ],
        'Rings' => [
            'Damage +1' => ['Cost' => 25,  'Damage' => 1, 'Armor' => 0],
            'Damage +2' => ['Cost' => 50,  'Damage' => 2, 'Armor' => 0],
            'Damage +3' => ['Cost' => 100, 'Damage' => 3, 'Armor' => 0],
            'Defence +1' => ['Cost' => 20,  'Damage' => 0, 'Armor' => 1],
            'Defence +2' => ['Cost' => 40,  'Damage' => 0, 'Armor' => 2],
            'Defence +3' => ['Cost' => 80,  'Damage' => 0, 'Armor' => 3],
        ],
    ];

    private array $boss = [
        'Hit' => 0,
        'Damage' => 0,
        'Armor' => 0,
    ];

    private array $player = [
        'Hit' => 100,
        'Damage' => 0,
        'Armor' => 0,
        'Cost' => 0,
        'Equipped' => [],
    ];

    private $part1;
    private $part2;

    protected function initialize(): void
    {
        if ($this->isTestMode()) {
            $this->player['Hit'] = 8;
        }
        $this->setBossStats($this->getInput());
        $this->part1 = INF;
        $this->part2 = 0;
        foreach ($this->nextBuy() as $player) {
            if ($this->fight($player)) {
                if ($player['Cost'] < $this->part1) {
                    $this->part1 = $player['Cost'];
                }
            } else {
                if ($player['Cost'] > $this->part2) {
                    $this->part2 = $player['Cost'];
                }
            }
        }
    }

    public function part1()
    {
        return $this->part1;
    }

    public function part2()
    {
        return $this->part2;
    }

    private function setBossStats(PuzzleInput $input): void
    {
        foreach (explode("\n", $input->getData()) as $line) {
            if (preg_match('/^Hit Points:\s(\d+)$/', $line, $matches)) {
                $this->boss['Hit'] = (int) $matches[1];
            }
            if (preg_match('/^Damage:\s(\d+)$/', $line, $matches)) {
                $this->boss['Damage'] = (int) $matches[1];
            }
            if (preg_match('/^Armor:\s(\d+)$/', $line, $matches)) {
                $this->boss['Armor'] = (int) $matches[1];
            }
        }
    }

    /**
     * @return bool
     */
    private function fight($player)
    {
        $boss = $this->boss;
        while (true) {
            $deals = max($player['Damage'] - $boss['Armor'], 1);
            $boss['Hit'] -= $deals;
            if ($boss['Hit'] <= 0) {
                return true;
            }

            $deals = max($boss['Damage'] - $player['Armor'], 1);
            $player['Hit'] -= $deals;
            if ($player['Hit'] <= 0) {
                return false;
            }
        }
    }

    /**
     * 1 weapon
     * 0-* Armor
     * 0-2 Rings.
     *
     * Create all combinations
     *
     * @psalm-return \Generator<int, mixed, mixed, void>
     */
    private function nextBuy(): \Generator
    {
        foreach ($this->shop['Weapons'] as $weaponName => $weaponStats) {
            foreach ($this->shop['Armor'] as $armorName => $armorStats) {
                foreach (Generator::combinationsFixedSize($this->shop['Rings'], 2) as $ringFixedComb) {
                    foreach (Generator::combinations($ringFixedComb) as $ringComb) {
                        $player = $this->player;
                        $player = $this->addItemToPlayer($player, $weaponName, $weaponStats);

                        foreach ($ringComb as $ringName => $ringStats) {
                            $player = $this->addItemToPlayer($player, $ringName, $ringStats);
                        }

                        //retrun without armor first
                        yield $player;

                        $player = $this->addItemToPlayer($player, $armorName, $armorStats);

                        yield $player;
                    }
                }
            }
        }
    }

    private function addItemToPlayer($player, $itemName, $itemStats)
    {
        $player['Damage'] += $itemStats['Damage'];
        $player['Armor'] += $itemStats['Armor'];
        $player['Cost'] += $itemStats['Cost'];
        $player['Equipped'][] = $itemName;

        return $player;
    }
}
