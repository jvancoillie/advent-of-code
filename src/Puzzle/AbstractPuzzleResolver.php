<?php

namespace App\Puzzle;

use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractPuzzleResolver
{
    abstract public function main(PuzzleInput $input, OutputInterface $output);
}