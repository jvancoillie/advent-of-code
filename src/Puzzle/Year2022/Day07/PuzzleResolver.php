<?php

namespace App\Puzzle\Year2022\Day07;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2022/day/7
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 95437;
    protected static int|string $testPart2Expected = 24933642;

    protected static int|string $part1Expected = 1723892;
    protected static int|string $part2Expected = 8474158;

    public const FILESYSTEM_SPACE = 70000000;
    public const UNUSED_SPACE = 30000000;
    public const DIRECTORY_MAX_SIZE = 100000;

    public function part1(): int
    {
        [$tree] = $this->makeTreeSize($this->getInput()->getArrayData());

        return $this->sumDirectoriesSizeAtMost($tree);
    }

    public function part2(): int
    {
        [$tree] = $this->makeTreeSize($this->getInput()->getArrayData());

        return $this->findSmallestDirectorySizeToFreeUpSpace(
            $tree,
            $tree['size'] - self::FILESYSTEM_SPACE + self::UNUSED_SPACE,
            self::FILESYSTEM_SPACE
        );
    }

    private function makeTreeSize(array $commands, int $step = 0): array
    {
        $tree = ['size' => 0];

        for ($i = $step; $i < count($commands); ++$i) {
            $command = $commands[$i];
            if (str_starts_with($command, '$')) {
                if (preg_match('/\$ cd (?P<directory>.*)/', $command, $m)) {
                    if ('..' === $m['directory']) {
                        return [$tree, $i];
                    }

                    [$sub, $i] = $this->makeTreeSize($commands, $i + 1);

                    $tree[$m['directory']] = $sub;
                    $tree['size'] += $sub['size'];
                }
                continue;
            }

            if (str_starts_with($command, 'dir')) {
                continue;
            }

            [$size] = explode(' ', $command);
            $tree['size'] += (int) $size;
        }

        return [$tree, $i];
    }

    private function sumDirectoriesSizeAtMost(array $directories, int $maxSize = self::DIRECTORY_MAX_SIZE): int
    {
        $size = 0;
        foreach ($directories as $index => $data) {
            if ('size' === $index) {
                if ($data <= $maxSize) {
                    $size += $data;
                }
                continue;
            }
            $size += $this->sumDirectoriesSizeAtMost($data, $maxSize);
        }

        return $size;
    }

    private function findSmallestDirectorySizeToFreeUpSpace(array $directories, int $needed, int $min): int
    {
        foreach ($directories as $index => $data) {
            if ('size' === $index) {
                if ($data >= $needed) {
                    if ($data < $min) {
                        $min = $data;
                    }
                }
                continue;
            }

            $min = $this->findSmallestDirectorySizeToFreeUpSpace($data, $needed, $min);
        }

        return $min;
    }
}
