<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Puzzle\AbstractPuzzleResolver;

/**
 * Class PuzzleResolver.
 *
 * @see <?php echo $puzzleLink; ?><?php echo "\n"; ?>
 */
class PuzzleResolver extends AbstractPuzzleResolver
{
    protected static int|string $testPart1Expected = 0;
    protected static int|string $testPart2Expected = 0;

    protected static int|string $part1Expected = 0;
    protected static int|string $part2Expected = 0;

    public function part1()
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        return $ans;
    }

    public function part2()
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        return $ans;
    }
}
