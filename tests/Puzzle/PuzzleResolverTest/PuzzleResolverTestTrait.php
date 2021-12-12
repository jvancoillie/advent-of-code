<?php

namespace App\Tests\Puzzle\PuzzleResolverTest;

use App\Puzzle\AbstractPuzzleResolver;
use App\Puzzle\PuzzleInput;
use Symfony\Component\Console\Output\ConsoleOutput;

trait PuzzleResolverTestTrait
{
    public function getTestInputData($year, $day, $isTest): bool|string
    {
        $inputFileName = $isTest ? 'test.txt' : 'input.txt';
        $inputFilePath = sprintf('src/Puzzle/Year%d/Day%s/input/%s', $year, $day, $inputFileName);

        return file_get_contents($inputFilePath);
    }

    public function getPuzzleResolverInstance($withTestData = true): AbstractPuzzleResolver
    {
        preg_match('/Year(?P<year>\d+)(.*)Day(?P<day>\d+)/', static::class, $matches);

        $options = ['mode' => $withTestData ? AbstractPuzzleResolver::TEST_MODE : AbstractPuzzleResolver::PROD_MODE];
        $output = new ConsoleOutput();
        $data = $this->getTestInputData($matches['year'], $matches['day'], $withTestData);

        $args = [new PuzzleInput($data), $output, $options];
        $className = sprintf('\\App\\Puzzle\\Year%d\\Day%s\\PuzzleResolver', $matches['year'], $matches['day']);

        return new $className(...$args);
    }

    /**
     * @dataProvider internalPuzzleResolverTestProvider
     */
    public function testPuzzleResolver(bool $withTestData, string $partMethod): void
    {
        $puzzleResolver = $this->getPuzzleResolverInstance($withTestData);

        $callablePartResult = [$puzzleResolver, $partMethod];

        if (!\is_callable($callablePartResult)) {
            throw new \InvalidArgumentException(sprintf('the part method "%s" of class "%s" is not callable', $partMethod, $puzzleResolver::class));
        }

        $partExpectedMethod = sprintf('get%s%sExpected', $withTestData ? 'Test' : '', ucfirst($partMethod));
        $callablePartExpected = [$puzzleResolver, $partExpectedMethod];

        if (!\is_callable($callablePartExpected)) {
            throw new \InvalidArgumentException(sprintf('the part method Expected "%s" of class "%s" is not callable', $partExpectedMethod, $puzzleResolver::class));
        }

        $this->assertSame($callablePartResult(), $callablePartExpected());
    }

    /**
     * @return PuzzleResolverTestModel[]
     */
    abstract public function puzzleResolverTestProvider(): iterable;

    public function internalPuzzleResolverTestProvider(): \Generator
    {
        foreach ($this->puzzleResolverTestProvider() as $puzzleResolverTestModel) {
            if (!$puzzleResolverTestModel instanceof PuzzleResolverTestModel) {
                throw new \LogicException(sprintf('puzzleResolverTestProvider must return %s collection', PuzzleResolverTestModel::class));
            }

            foreach ($puzzleResolverTestModel->getPartMethods() as $partMethod) {
                yield sprintf('%s %s',
                    $puzzleResolverTestModel->isTest() ? 'test' : 'prod',
                    $partMethod,
                ) => [
                    $puzzleResolverTestModel->isTest(),
                    $partMethod,
                ];
            }
        }
    }
}
