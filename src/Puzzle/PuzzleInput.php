<?php

namespace App\Puzzle;

class PuzzleInput
{
    public function __construct(private $data)
    {
    }

    public function getData()
    {
        return $this->data;
    }

    public function getArrayData(): array
    {
        return array_map('trim', explode("\n", $this->getData()));
    }
}
