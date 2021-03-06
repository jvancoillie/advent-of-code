<?php

namespace App\Puzzle\Year2015\Day03;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $x = $y = 0;
        $houses = [[0]];
        foreach (str_split($input->getData()) as $d) {
            switch ($d) {
                case '<':
                    $x -= 1;
                    break;
                case '>':
                    $x += 1;
                    break;
                case '^':
                    $y -= 1;
                    break;
                case 'v':
                    $y += 1;
                    break;
            }
            if (isset($houses[$x][$y])) {
                $houses[$x][$y]++;
            } else {
                $houses[$x][$y] = 0;
            }
        }
        $count = $this->countHouses($houses);

        $output->writeln("<info>Part 1 : $count</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $coords = [
            'santa' => ['x' => 0, 'y' => 0],
            'robo' => ['x' => 0, 'y' => 0],
        ];
        $houses = [[0]];
        foreach (str_split($input->getData()) as $key => $d) {
            $turn = ($key % 2 === 0) ? 'santa' : 'robo';
            switch ($d) {
                case '<':
                    $coords[$turn]['x'] -= 1;
                    break;
                case '>':
                    $coords[$turn]['x'] += 1;
                    break;
                case '^':
                    $coords[$turn]['y'] -= 1;
                    break;
                case 'v':
                    $coords[$turn]['y'] += 1;
                    break;
            }
            if (isset($houses[$coords[$turn]['x']][$coords[$turn]['y']])) {
                $houses[$coords[$turn]['x']][$coords[$turn]['y']]++;
            } else {
                $houses[$coords[$turn]['x']][$coords[$turn]['y']] = 0;
            }
        }
        $count = $this->countHouses($houses);

        $output->writeln("<info>Part 2 : $count</info>");
    }

    public function countHouses($houses)
    {
        $count = 0;
        foreach ($houses as $house) {
            $count += count($house);
        }

        return $count;
    }
}