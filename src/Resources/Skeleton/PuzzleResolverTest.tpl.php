<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Tests\Puzzle\PuzzleResolverTest\PuzzleResolverTestModel;
use App\Tests\Puzzle\PuzzleResolverTest\PuzzleResolverTestTrait;
use PHPUnit\Framework\TestCase;

class PuzzleResolverTest extends TestCase
{
    use PuzzleResolverTestTrait;

    public function puzzleResolverTestProvider(): iterable
    {
        yield PuzzleResolverTestModel::create(true)
            ->addPartMethod('part1')
            ->addPartMethod('part2')
        ;

        yield PuzzleResolverTestModel::create(false)
            ->addPartMethod('part1')
            ->addPartMethod('part2')
         ;
    }
}
