<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output)
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach (explode("\n", $input->getData()) as $line){

        }
        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;
        foreach (explode("\n", $input->getData()) as $line){

        }
        $output->writeln("<info>Part 2 : $ans</info>");
    }
}