<?php

namespace App\Utils;

class Generator
{
    /**
     * @psalm-return \Generator<int, array|mixed, mixed, void>
     */
    public static function combinations(array $dataset): \Generator
    {
        if (0 === count($dataset)) {
            yield [];
        } else {
            $key = array_key_first($dataset);
            $current = array_shift($dataset);

            $remaining = $dataset;
            $withoutFirst = self::combinations($remaining);
            foreach ($withoutFirst as $comb) {
                yield $comb;
                $comb = array_merge($comb, [$key => $current]);
                yield $comb;
            }
        }
    }

    /**
     * @psalm-param 1|2 $size
     *
     * @psalm-return \Generator<int, array<mixed|null>, mixed, void>
     */
    public static function combinationsFixedSize(array $dataset, int $size = 1): \Generator
    {
        $originalLength = count($dataset);
        $remainingLength = $originalLength - $size + 1;

        for ($i = 0; $i < $remainingLength; ++$i) {
            /** @var int $key */
            $key = array_key_first($dataset);
            $current = array_shift($dataset);

            if (1 === $size) {
                yield [$key => $current];
            } else {
                $remaining = $dataset;

                foreach (self::combinationsFixedSize($remaining, $size - 1) as $comb) {
                    yield array_merge($comb, [$key => $current]);
                }
            }
        }
    }

    /**
     * @psalm-return \Generator<int, array, mixed, void>
     */
    public static function permutations($items): \Generator
    {
        //        if (count($items) === 0) {
        //            yield [];
        //        }
        //
        //        $firstItem = array_shift($items);
        //        foreach (self::permutations($items) as $perms) {
        //            for ($i = 0; $i <= count($perms); $i++) {
        //                yield array_merge(array_slice($perms, 0, $i), [$firstItem], array_slice($perms, $i));
        //            }
        //        }

        if (0 === count($items)) {
            yield [];
        } else {
            $firstItem = array_shift($items);
            $permsWithoutFirst = self::permutations($items);

            foreach ($permsWithoutFirst as $perms) {
                for ($i = 0; $i <= count($perms); ++$i) {
                    yield array_merge(array_slice($perms, 0, $i), [$firstItem], array_slice($perms, $i));
                }
            }
        }
    }
}
