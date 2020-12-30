<?php


namespace App\Utils;


class Generator
{
    public static function combinations(array $dataset)
    {
        if (count($dataset) === 0) {
            yield [];
        }else{
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

    public static function combinationsFixedSize(array $dataset, $size=1)
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
}