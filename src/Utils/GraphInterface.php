<?php

namespace App\Utils;

interface GraphInterface
{
    public function getNeighbors(NodeInterface $node): array;

    public function getDistance(NodeInterface $nodeA, NodeInterface $nodeB): float;
}
