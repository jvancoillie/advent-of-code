<?php

namespace App\Puzzle\Year2016\Day03;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2016/day/3
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    /**
     * @return void
     */
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output): void
    {
        $triangles = [];
        foreach (explode("\n", $input->getData()) as $line) {
            $triangles[] = array_map('intval', preg_split("/[\s]+/", trim($line)));
        }

        $ans = $this->countValidTriangles($triangles);

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output): void
    {
        $cols = [[], [], []];
        $triangles = [];
        foreach (explode("\n", $input->getData()) as $line) {
            [$col1, $col2, $col3] = array_map('intval', preg_split("/[\s]+/", trim($line)));
            $cols[0][] = $col1;
            $cols[1][] = $col2;
            $cols[2][] = $col3;
            if (3 === count($cols[0])) {
                foreach ($cols as $col) {
                    $triangles[] = $col;
                }
                $cols = [[], [], []];
            }
        }
        $ans = $this->countValidTriangles($triangles);

        $output->writeln("<info>Part 2 : $ans</info>");
    }

    /**
     * @param int[][] $triangles
     *
     * @psalm-param list<list<int>> $triangles
     *
     * @psalm-return 0|positive-int
     */
    private function countValidTriangles(array $triangles): int
    {
        $valid = 0;

        foreach ($triangles as $triangle) {
            sort($triangle);
            if ($triangle[0] + $triangle[1] > $triangle[2]) {
                ++$valid;
            }
        }

        return $valid;
    }
}
