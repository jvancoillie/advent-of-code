<?php

namespace App\Puzzle;

class PuzzleInput
{
    public function __construct(private $data)
    {
    }

    public function getData(bool $trim = true)
    {
        if ($trim) {
            return trim($this->data);
        }

        return $this->data;
    }

    public function getArrayData($trim = true): array
    {
        $data = explode("\n", $this->getData());

        if ($trim) {
            return array_map('trim', $data);
        }

        return $data;
    }
}
