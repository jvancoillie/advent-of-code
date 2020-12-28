<?php

namespace App\Puzzle\Year2015\Day14;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PuzzleResolver
 * @see https://adventofcode.com/2015/day/14
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    private $reindeers = [];

    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        dump($options);
        $this->createReindeers($input);
        $this->part1($output);
        $this->part2($output);
    }

    public function part1(OutputInterface $output)
    {
        $ans = 0;
        $time = 2503;

        foreach($this->reindeers as $reindeer){
             $dist = $reindeer['speed'] * (floor($time / ($reindeer['fly'] + $reindeer['rest'])) * $reindeer['fly'] + min($time % ($reindeer['fly'] + $reindeer['rest']), $reindeer['fly']));
             if($dist > $ans){
                $ans = $dist;
             }
        }

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(OutputInterface $output)
    {
        $points = [];
        for ($i = 1; $i <= 2503; $i++) {
            $winners = null;
            $best = 0;
            foreach($this->reindeers as $key => $reindeer) {
                $dist = $reindeer['speed'] * (floor($i / ($reindeer['fly'] + $reindeer['rest'])) * $reindeer['fly'] + min($i % ($reindeer['fly'] + $reindeer['rest']), $reindeer['fly']));
                if($dist > $best){
                    $best = $dist;
                    $winners = [$key];
                }elseif($dist === $best && $best > 0){
                    $winners[] = $key;
                }
            }

            foreach ($winners as $winner) {
                if(isset($points[$winner])){
                    $points[$winner]++;
                }else{
                    $points[$winner] = 1;
                }
            }

        }

        $ans = max($points);
        $output->writeln("<info>Part 2 : $ans</info>");
    }

    public function createReindeers(PuzzleInput $input)
    {
        foreach (explode("\n", $input->getData()) as $line) {
            $this->parseLine($line);
        }
    }

    public function parseLine($line)
    {
        $pattern = '/^(?<reindeer>.*) can fly (?<speed>\d+) km\/s for (?<fly>\d+) seconds, but then must rest for (?<rest>\d+) seconds.$/';
        preg_match($pattern, $line, $matches);

        if (!$matches) {
            throw new \Exception("parsing action error");
        }

        $this->reindeers[] = [
            'reindeer' => $matches['reindeer'],
            'speed' => $matches['speed'],
            'fly' => $matches['fly'],
            'rest' => $matches['rest'],
        ];
    }
}