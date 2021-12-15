<?php

namespace App\Puzzle\Year2016\Day13;

class Maze implements GraphInterface
{
    /**
     * @var Tile[][]|array
     */
    private array $tiles = [];

    public function __construct(private int $favoriteNumber)
    {
    }

    public function toString(callable $renderer = null, string $rowDelimiter = "\n"): string
    {
        $renderer = $renderer ?: fn (Tile $tile) => $tile->getValue();
        ksort($this->tiles);
        $result = [];

        foreach ($this->tiles as $r => $row) {
            ksort($row);
            if (!isset($result[$r])) {
                $result[$r] = [];
            }

            foreach ($row as $c => $tile) {
                $result[$r][$c] = $renderer($tile);
            }
        }

        return implode($rowDelimiter, array_map('implode', $result));
    }

    public function createPoint(int $x, int $y)
    {
        if (isset($this->tiles[$y][$x])) {
            return $this->tiles[$y][$x];
        }

        $count = substr_count(decbin(($x * $x + 3 * $x + 2 * $x * $y + $y + $y * $y) + $this->favoriteNumber), '1');

        $tile = new Tile($x, $y, $count % 2 ? '#' : '.');

        $this->tiles[$y][$x] = $tile;

        return $tile;
    }

    public function getNeighbors(NodeInterface $node): array
    {
        if (!$node instanceof Tile) {
            throw new \Exception('$node must be instance of Tile');
        }

        $neighbors = [];

        $directions = [
                        [-1, 0],
            [0, -1],          [0, 1],
                        [1, 0],
        ];

        foreach ($directions as [$dx, $dy]) {
            $y = $node->getY() + $dy;
            $x = $node->getX() + $dx;
            if (!isset($this->tiles[$y][$x])) {
                $this->createPoint($x, $y);
            }

            if (!in_array($this->tiles[$y][$x]->getValue(), ['#'], true)) {
                $neighbors[] = $this->tiles[$y][$x];
            }
        }

        return $neighbors;
    }

    public function getDistance(NodeInterface $nodeA, NodeInterface $nodeB): float
    {
        if (!$nodeA instanceof Tile) {
            throw new \Exception('$nodeA must be instance of Tile');
        }

        if (!$nodeB instanceof Tile) {
            throw new \Exception('$nodeB must be instance of Tile');
        }

        $p = $nodeB->getY() - $nodeA->getY();
        $q = $nodeB->getX() - $nodeA->getX();

        return sqrt($p * $p + $q * $q);
    }
}
