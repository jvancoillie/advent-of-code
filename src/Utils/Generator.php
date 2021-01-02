<?php


namespace App\Utils;


class Generator
{
    public static function combinations(array $dataset)
    {
        if (count($dataset) === 0) {
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

    public static function combinationsFixedSize(array $dataset, $size = 1)
    {
        $originalLength = count($dataset);
        $remainingLength = $originalLength - $size + 1;

        for ($i = 0; $i < $remainingLength; ++$i) {
            $key = array_key_first($dataset);
            $current = array_shift($dataset);

            if (1 === $size) {
                yield [$key => $current];
            } else {
                $remaining = $dataset;

                foreach (self::combinationsFixedSize($remaining, $size - 1) as $comb) {
                    $comb = array_merge($comb, [$key => $current]);
                    yield $comb;
                }
            }
        }
    }

    public static function permutations($items)
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

        if (count($items) === 0) {
            yield [];
        }else{
            $firstItem = array_shift($items);
            $permsWithoutFirst = self::permutations($items);

            foreach ($permsWithoutFirst as $perms){
                for($i=0; $i<=count($perms); $i++){
                    yield array_merge(array_slice($perms, 0, $i), [$firstItem], array_slice($perms, $i));
                }
            }
        }



    }
}