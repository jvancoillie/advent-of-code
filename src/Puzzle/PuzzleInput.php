<?php

namespace App\Puzzle;

class PuzzleInput
{
    private $data;

    /**
     * PuzzleInput constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}