<?php

namespace App\Command;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolverCommand extends Command
{
    protected static $defaultName = 'puzzle:resolve';

    /**
     * @return void
     */
    protected function configure()
    {
        $currentYear = (new \DateTime())->format('Y');
        $currentDay = (new \DateTime())->format('d');
        $this
            ->setDescription('Outputs the solutions of a Puzzles for a given event')
            ->addOption('year', 'y', InputOption::VALUE_REQUIRED, 'the year of the event', $currentYear)
            ->addOption('day', 'd', InputOption::VALUE_REQUIRED, 'the day of the event', $currentDay)
            ->addOption('test', null, InputOption::VALUE_NONE, 'If set, run with test input')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getOption('year');
        $day = sprintf('%02d', $input->getOption('day'));

        $link = sprintf('https://adventofcode.com/%d/day/%d', $year, $day);

        $isTest = $input->getOption('test');
        $options = ['mode' => $isTest ? AbstractPuzzleResolver::TEST_MODE : AbstractPuzzleResolver::PROD_MODE];

        $inputFileName = $isTest ? 'test.txt' : 'input.txt';
        $inputFilePath = sprintf('src/Puzzle/Year%d/Day%s/input/%s', $year, $day, $inputFileName);

        $data = file_get_contents($inputFilePath);

        try {
            $args = [new PuzzleInput($data), $output, $options];

            $resolverInstance = $this->instantiateClass(sprintf('\\App\\Puzzle\\Year%d\\Day%s\\PuzzleResolver', $year, $day), $args);

            $callablePart1 = [$resolverInstance, 'part1'];
            $callablePart2 = [$resolverInstance, 'part2'];
            $callablePart1Expected = [$resolverInstance, $isTest ? 'getTestPart1Expected' : 'getPart1Expected'];
            $callablePart2Expected = [$resolverInstance, $isTest ? 'getTestPart2Expected' : 'getPart2Expected'];
        } catch (\Error) {
            $output->writeln(sprintf('<error>No class found for day %d of year %d</error>', $day, $year));

            return Command::FAILURE;
        }

        $output->writeln(sprintf('<href=%1$s>%1$s</>', $link));
        $output->writeln([
            sprintf('<info>==========</info> <comment>DAY %2$s %1$s, MODE %3$s</comment> <info>==========</info> ', $year, $day, $isTest ? 'Test' : 'Prod'),
            ' ',
        ]);

        if (!\is_callable($callablePart1)) {
            throw new \InvalidArgumentException(sprintf('the part1 method of class \\App\\Puzzle\\Year%d\\Day%s is not callable', $year, $day));
        }

        if (!\is_callable($callablePart2)) {
            throw new \InvalidArgumentException(sprintf('the part2 method of class \\App\\Puzzle\\Year%d\\Day%s is not callable', $year, $day));
        }

        $startTime = microtime(true);

        $this->outputPartResult($output, 1, $callablePart1(), $callablePart1Expected());

        $this->outputPartResult($output, 2, $callablePart2(), $callablePart2Expected());

        $output->writeln([
             ' ',
             sprintf('<info>==========</info> <comment>Execution time: %01.4f</comment> <info>==========</info> ', microtime(true) - $startTime),
         ]);

        return Command::SUCCESS;
    }

    protected function outputPartResult(OutputInterface $output, int $part, string|int $result, string|int $expected)
    {
        $output->writeln(sprintf('  <options=bold,underscore,conceal>Part %d</> <options=blink>:</> <fg=%s>%s</>', $part, ($result === $expected) ? 'green' : 'red', $result));
    }

    protected function instantiateClass(string $class, array $args): object
    {
        return new $class(...$args);
    }
}
