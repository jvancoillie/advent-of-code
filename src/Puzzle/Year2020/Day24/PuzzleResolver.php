<?php

namespace App\Puzzle\Year2020\Day24;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    const BLACK = '#';
    const WHITE = 'O';
    private $directions = [
        'w'  => [-1, 0],
        'e'  => [1,  0],
        'ne' => [0,  1],
        'nw' => [-1, 1],
        'se' => [1, -1],
        'sw' => [0, -1],
    ];
    private $minX = 0;
    private $minY = 0;
    private $maxX = 0;
    private $maxY = 0;
    private $floor = [];

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        foreach (explode("\n", $input->getData()) as $data) {
            $moves = $this->parseLine($data);
            $this->doMoves($moves);

        }

        $blackTiles = $this->countBlackTiles();

        $output->writeln("<info>Part 1 : $blackTiles black tiles</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output, $floor)
    {
        for ($i = 0; $i < 100; $i++) {
            $this->dayFlipping();
        }

        $blackTiles = $this->countBlackTiles();

        $output->writeln("<info>Part 2 : $blackTiles black tiles</info>");
    }

    /**
     * e, se, sw, w, nw, and ne
     */
    public function parseLine($string)
    {
        $split = str_split($string);
        $moves = [];
        for ($i = 0; $i < count($split); $i++) {
            $move = $split[$i];
            if ($split[$i] === "s" || $split[$i] === "n") {
                if (isset($split[$i + 1]) && ($split[$i + 1] === "e" || $split[$i + 1] === "w")) {
                    $move .= $split[$i + 1];
                    $i++;
                }
            }
            $moves[] = $move;
        }

        return $moves;
    }

    public function doMoves($moves)
    {
        $x = $y = 0;

        foreach ($moves as $move) {
            $x += $this->directions[$move][0];
            $y += $this->directions[$move][1];
        }

        $this->updateFloorSize($x, $y);

        if (isset($this->floor[$x][$y])) {
            $this->floor[$x][$y] = ($this->floor[$x][$y] === self::BLACK) ? self::WHITE : self::BLACK;
        } else {
            $this->floor[$x][$y] = self::BLACK;
        }
    }

    public function countBlackTiles()
    {
        $sum = 0;
        foreach ($this->floor as $line) {
            foreach ($line as $tile) {
                if ($tile === self::BLACK) {
                    $sum++;
                }
            }
        }

        return $sum;
    }

    public function dayFlipping()
    {
        $newFloor = [];

        $this->extendFloor();

        for ($x = $this->minX; $x <= $this->maxX; $x++) {
            for ($y = $this->minY; $y <= $this->maxY; $y++) {
                $colors = $this->countNeighborsColors($x, $y);
                $tile = $this->floor[$x][$y] ?? self::WHITE;
                if ($tile === self::WHITE && $colors[self::BLACK] === 2) {
                    $newFloor[$x][$y] = self::BLACK;
                } elseif ($tile === self::BLACK && ($colors[self::BLACK] === 0 || $colors[self::BLACK] > 2)) {
                    $newFloor[$x][$y] = self::WHITE;
                } else {
                    $newFloor[$x][$y] = $tile;
                }
            }
        }

        $this->floor =  $newFloor;
    }

    public function countNeighborsColors($x, $y)
    {
        $colors = [self::BLACK => 0, self::WHITE => 0];
        foreach ($this->directions as [$ax, $ay]) {
            $nx = $x + $ax;
            $ny = $y + $ay;
            if (isset($this->floor[$nx][$ny])) {
                $colors[$this->floor[$nx][$ny]]++;
            } else {
                $colors[self::WHITE]++;
            }
        }

        return $colors;
    }

    public function extendFloor()
    {
        $this->minX--;
        $this->maxX++;
        $this->minY--;
        $this->maxY++;
    }

    /**
     * @param int $x
     * @param int $y
     */
    private function updateFloorSize(int $x, int $y): void
    {
        if ($x > $this->maxX) {
            $this->maxX = $x;
        }
        if ($x < $this->minX) {
            $this->minX = $x;
        }
        if ($y > $this->maxY) {
            $this->maxY = $y;
        }
        if ($y < $this->minY) {
            $this->minY = $y;
        }
    }

    public function main(PuzzleInput $input, OutputInterface $output)
    {
        $floor = $this->part1($input, $output);

        $this->part2($input, $output, $floor);
    }

}