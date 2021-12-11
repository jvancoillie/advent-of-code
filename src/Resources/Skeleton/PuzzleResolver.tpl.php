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
    protected static int $testPart1Expected = 0;
    protected static int $testPart2Expected = 0;

    protected static int $part1Expected = 0;
    protected static int $part2Expected = 0;

    public function part1()
    {
        $ans = 0;

        $data = explode("\n", $this->getInput()->getData());

        return $ans;
    }

    public function part2()
    {
        $ans = 0;

        $data = explode("\n", $this->getInput()->getData());

        return $ans;
    }
}