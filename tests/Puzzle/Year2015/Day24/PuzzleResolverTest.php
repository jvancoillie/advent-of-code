<?php

namespace App\Tests\Puzzle\Year2015\Day24;

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
//         TODO improve this puzzle
//        yield PuzzleResolverTestModel::create(false)
//            ->addPartMethod('part1')
//            ->addPartMethod('part2')
//         ;
    }
}
