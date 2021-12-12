<?php

namespace App\Puzzle;

use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractPuzzleResolver
{
    private array $options;

    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 0;
    protected static int|string $part2Expected = 0;

    public function __construct(private PuzzleInput $input, private OutputInterface $output, array $options = [])
    {
        $this->options = $options;
        $this->initialize();
    }

    public static function getTestPart1Expected(): int|string
    {
        return static::$testPart1Expected;
    }

    public static function getTestPart2Expected(): int|string
    {
        return static::$testPart2Expected;
    }

    public static function getPart1Expected(): int|string
    {
        return static::$part1Expected;
    }

    public static function getPart2Expected(): int|string
    {
        return static::$part2Expected;
    }

    public function getInput(): PuzzleInput
    {
        return $this->input;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function initialize(): void
    {
        // void method, can be used before part1 & part2 is called
    }

    abstract public function part1();

    abstract public function part2();
}
