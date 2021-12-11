<?php

namespace App\Tests\Puzzle\PuzzleResolverTest;

class PuzzleResolverTestModel
{
    private array $partMethods = [];

    private function __construct(private bool $withTestInput)
    {
    }

    public static function create(bool $withTestInput, ): self
    {
        return new self($withTestInput);
    }

    public function isTest(): bool
    {
        return $this->withTestInput;
    }

    public function addPartMethod($methodName): self
    {
        $this->partMethods[] = $methodName;

        return $this;
    }

    public function getPartMethods(): array
    {
        return $this->partMethods;
    }
}
