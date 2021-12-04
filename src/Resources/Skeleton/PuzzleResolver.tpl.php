<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Class PuzzleResolver
* @see <?php echo $puzzleLink; ?><?php echo "\n"; ?>
*/
class PuzzleResolver extends AbstractPuzzleResolver
{
    public function main(PuzzleInput $input, OutputInterface $output, $options = [])
    {
        $this->part1($input, $output);
        $this->part2($input, $output);
    }

    public function part1(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;

        $data = explode("\n", $input->getData());

        $output->writeln("<info>Part 1 : $ans</info>");
    }

    public function part2(PuzzleInput $input, OutputInterface $output)
    {
        $ans = 0;

        $data = explode("\n", $input->getData());

        $output->writeln("<info>Part 2 : $ans</info>");
    }
}