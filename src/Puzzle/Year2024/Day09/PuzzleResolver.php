<?php

namespace App\Puzzle\Year2024\Day09;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 *  TODO need refactor
 *
 * @see https://adventofcode.com/2024/day/9
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 1928;
    protected static int|string $testPart2Expected = 2858;

    protected static int|string $part1Expected = 6310675819476;
    protected static int|string $part2Expected = 6335972980679;

    public function part1(): int
    {
        $diskMap = $this->getDisk();

        $reorder = $this->moveLastToFirstDot($diskMap);

        return $this->checksum($reorder);
    }

    public function part2(): int
    {
        $diskMap = $this->getDisk();

        $reorder = $this->moveFileToFirstFreeSpace($diskMap);

        return $this->checksum($reorder);
    }

    private function getDisk(): array
    {
        $string = $this->getInput()->getData();

        $diskMap = [];

        for ($i = 0; $i < strlen($string); ++$i) {
            if (0 === $i % 2) {
                for ($j = 0; $j < $string[$i]; ++$j) {
                    $diskMap[] = $i / 2;
                }
            } else {
                for ($j = 0; $j < $string[$i]; ++$j) {
                    $diskMap[] = '.';
                }
            }
        }

        return $diskMap;
    }

    public function moveLastToFirstDot(array $diskMap): array
    {
        $end = count($diskMap) - 1;
        for ($i = 0; $i < $end; ++$i) {
            if ('.' === $diskMap[$i]) {
                $lastNumber = null;
                for ($j = $end; $j > $i; --$j) {
                    if ('.' !== $diskMap[$j]) {
                        $lastNumber = $diskMap[$j];
                        $diskMap[$j] = '.';
                        $end = $j - 1;
                        break;
                    }
                }
                if (null !== $lastNumber) {
                    $diskMap[$i] = $lastNumber;
                    continue;
                }
                break;
            }
        }

        return $diskMap;
    }

    public function moveFileToFirstFreeSpace(array $diskMap): array
    {
        $end = count($diskMap) - 1;
        for ($i = $end; $i > 0; --$i) {
            if ('.' === $diskMap[$i]) {
                continue;
            }
            $filePos = $i;
            $file = $diskMap[$i];
            $fileLength = 0;

            while ($i >= 0 && $diskMap[$i] === $file) {
                ++$fileLength;
                --$i;
            }
            ++$i;

            for ($j = 0; $j < $i; ++$j) {
                $length = 0;
                if ('.' === $diskMap[$j]) {
                    do {
                        ++$length;
                        if ($length === $fileLength) {
                            $k = 0;
                            while ($k < $length) {
                                $diskMap[$j - $k] = $file;
                                $diskMap[$filePos - $k] = '.';
                                ++$k;
                            }
                            break 2;
                        }
                    } while ('.' === $diskMap[++$j]);
                }
            }
        }

        return $diskMap;
    }

    private function checksum(array $diskMap): int
    {
        $checksum = 0;
        for ($i = 0; $i < count($diskMap); ++$i) {
            if ('.' === $diskMap[$i]) {
                continue;
            }
            $checksum += (int) $diskMap[$i] * $i;
        }

        return $checksum;
    }
}
