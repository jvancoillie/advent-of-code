<?php

namespace App\Puzzle\Year2015\Day24;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2015/day/24
 *
 * Not found @see https://www.reddit.com/r/adventofcode/comments/3y1s7f/day_24_solutions/
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $packages = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $packages = explode("\n", $input->getData());
        $list = $this->createGroups(array_sum($packages) / 3, $packages);
        $min = INF;
        foreach ($list as $item) {
            $product = array_product($item);
            if ($product < $min) {
                $min = $product;
            }
        }

        $output->writeln("<info>Part 1 : $min</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $packages = explode("\n", $input->getData());
        $list = $this->createGroups(array_sum($packages) / 4, $packages);
        $min = INF;
        foreach ($list as $item) {
            $product = array_product($item);
            if ($product < $min) {
                $min = $product;
            }
        }

        $output->writeln("<info>Part 1 : $min</info>");
    }

    private function createGroups($weight, $packages = [], $group = [], $checked = [])
    {
        $list = [];

        while ($packages) {
            $package = array_pop($packages);
            $weightCheck = array_sum($group) + $package;
            if ($weight < $weightCheck) {
                $checked[] = $package;
                continue;
            }
            $newGroup = $group;
            $newGroup[] = $package;
            if ($weightCheck === $weight && $this->isValid(array_merge($checked, $packages), $weight)) {
                $list[] = $newGroup;
            }

            $list = array_merge($list, $this->createGroups($weight, $packages, $newGroup, $checked));
        }

        return array_unique($list, SORT_REGULAR);
    }

    private function isValid(array $group, $targetWeight, $weight = 0)
    {
        while ($group) {
            $item = array_pop($group);
            $newWeight = $weight + $item;
            if ($newWeight === $targetWeight) {
                return true;
            }
            if ($newWeight < $targetWeight && $this->isValid($group, $targetWeight, $newWeight)) {
                return true;
            }
        }

        return false;
    }
}
