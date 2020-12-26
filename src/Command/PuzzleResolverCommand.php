<?php

namespace App\Command;

use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolverCommand extends Command
{
    protected static $defaultName = 'puzzle:resolve';

    protected function configure()
    {
        $currentYear = (new \DateTime())->format("Y");
        $currentDay = (new \DateTime())->format("d");
        $this
            ->setDescription('Outputs the solutions of a Puzzles for a given event')
            ->addOption(
                'year',
                'y',
                InputOption::VALUE_REQUIRED,
                'the year of the event',
                $currentYear
            )
            ->addOption(
                'day',
                'd',
                InputOption::VALUE_REQUIRED,
                'the day of the event',
                $currentDay
            )
            ->addOption('test', null, InputOption::VALUE_NONE, 'If set, run with test input')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getOption('year');
        $day = $input->getOption('day');

        $link = sprintf("https://adventofcode.com/%d/day/%d", $year, $day);

        $isTest = $input->getOption('test');
        $inputFileName = $isTest?'test.txt':'input.txt';
        $inputFilePath = sprintf("src/Puzzle/Year%d/Day%s/input/%s", $year, $day, $inputFileName);

        try{
            $callable = [$this->instantiateClass(sprintf('\\App\\Puzzle\\Year%d\\Day%s\\PuzzleResolver',$year, $day)), 'main'];
        }catch (\Error $e){
            $output->writeln(sprintf('<error>No class found for day %d of year %d</error>', $day, $year));
            return Command::FAILURE;
        }

        $output->writeln(sprintf('<info><href=%1$s>%1$s</><info>',$link));
        $output->writeln(sprintf('<info>=========  DAY:  %1$s-%2$s, MODE: %3$s ========= <info>', $year, $day, $isTest?"Test":"Prod"));
        if (!\is_callable($callable)) {
            throw new \InvalidArgumentException(sprintf('the main methode of class \\App\\Puzzle\\Year%d\\Day%s is not callable',$year, $day ));
        }

        $data = file_get_contents($inputFilePath);

        $startTime = microtime(true);
        $callable(new PuzzleInput($data), $output);
        $output->writeln('<comment>Execution time: '.(microtime(true) - $startTime).'</comment>');

        return Command::SUCCESS;
    }

    /**
     * Returns an instantiated class.
     *
     * @return object
     */
    protected function instantiateClass(string $class)
    {
        return new $class();
    }
}