<?php

namespace App\Puzzle;

use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractPuzzleResolver
{
    private PuzzleInput $input;
    private OutputInterface $output;
    private array $options;

    protected static int $testPart1Expected = 0;
    protected static int $testPart2Expected = 0;

    protected static int $part1Expected = 0;
    protected static int $part2Expected = 0;

    public function __construct(PuzzleInput $input, OutputInterface $output, array $options)
    {
        $this->input = $input;
        $this->output = $output;
        $this->options = $options;
    }

    public static function getTestPart1Expected(): int
    {
        return self::$testPart1Expected;
    }

    public static function getTestPart2Expected(): int
    {
        return self::$testPart2Expected;
    }

    public static function getPart1Expected(): int
    {
        return self::$part1Expected;
    }

    public static function getPart2Expected(): int
    {
        return self::$part2Expected;
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

    public function main()
    {
        // void method, can be used before part1 & part2 is called
    }

    abstract public function part1();

    abstract public function part2();
}
