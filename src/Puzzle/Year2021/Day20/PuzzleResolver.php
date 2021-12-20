<?php

namespace App\Puzzle\Year2021\Day20;

use App\Puzzle\AbstractPuzzleResolver;
use App\Utils\Grid;

/**
 * Class PuzzleResolver.
 *
 * @see https://adventofcode.com/2021/day/20
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 35;
    protected static int|string $testPart2Expected = 3351;

    protected static int|string $part1Expected = 5249;
    protected static int|string $part2Expected = 15714;

    private array $image;
    private string $enhancementAlgorithm;

    public static array $directions = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 0],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

    private array $invertedPix = [
        '#' => '.',
        '.' => '#',
    ];

    public function initialize(): void
    {
        $data = explode("\n\n", $this->getInput()->getData());
        $this->enhancementAlgorithm = $data[0];

        foreach (explode("\n", $data[1]) as $line) {
            $this->image[] = str_split($line);
        }
    }

    public function part1(): int
    {
        $image = $this->enhanceImage($this->image, 2);

        return Grid::count($image, '#');
    }

    public function part2(): int
    {
        $image = $this->enhanceImage($this->image, 50);

        return Grid::count($image, '#');
    }

    public function extractPixel($y, $x, $image, $hiddenPix): string
    {
        $binary = '';

        foreach (self::$directions as [$dy, $dx]) {
            $nx = $x + $dx;
            $ny = $y + $dy;
            $binary .= $image[$ny][$nx] ?? $hiddenPix;
        }

        $int = (int) bindec(str_replace(['#', '.'], [1, 0], $binary));

        return $this->enhancementAlgorithm[$int];
    }

    public function enhanceImage($image, $loop)
    {
        $defaultPix = '.';

        $startX = $startY = 0;
        $endY = count($image);
        $endX = count($image[0]);

        for ($i = 1; $i <= $loop; ++$i) {
            $newImage = [];
            for ($y = $startY - $i; $y < $endY + $i; ++$y) {
                for ($x = $startX - $i; $x < $endX + $i; ++$x) {
                    $newImage[$y][$x] = $this->extractPixel($y, $x, $image, $defaultPix);
                }
            }
            $defaultPix = ('.' === $defaultPix) ? $this->enhancementAlgorithm[0] : $this->invertedPix[$this->enhancementAlgorithm[0]];
            $image = $newImage;
        }

        return $image;
    }
}
