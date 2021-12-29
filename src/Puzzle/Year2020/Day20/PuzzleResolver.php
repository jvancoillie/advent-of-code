<?php

namespace App\Puzzle\Year2020\Day20;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2020/day/20
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 20899048083289;
    protected static int|string $testPart2Expected = 273;


    protected static int|string $part1Expected = 27798062994017;
    protected static int|string $part2Expected = 2366;

    private Puzzle $puzzle;

    protected function initialize(): void
    {
        $tilesData = explode("\n\n", $this->getInput()->getData());

        $tiles = [];
        foreach ($tilesData as $data) {
            [$idLine, $tileData] = explode(':', $data);
            preg_match('/^Tile\s+?(?<id>\d+)$/', $idLine, $mactches);
            $tile = new Tile($mactches['id'], trim($tileData));
            $tiles[$mactches['id']] = $tile;
        }

        $this->puzzle = new Puzzle($tiles);
    }

    public function part1(): int
    {
        return $this->puzzle->resolve();
    }

    public function part2(): int
    {
        $this->puzzle->resolve();
        return $this->puzzle->findSeaMonster();
    }
}
