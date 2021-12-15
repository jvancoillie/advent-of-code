<?php

namespace App\Puzzle\Year2016\Day13;

interface GraphInterface
{
    public function getNeighbors(NodeInterface $node): array;

    public function getDistance(NodeInterface $nodeA, NodeInterface $nodeB): float;
}
