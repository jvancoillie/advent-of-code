<?php


namespace App\Utils;


class Generator
{
    public static function combinations(array $dataset)
    {
        if (count($dataset) === 0) {
            yield [];
        }else{
            $current = $dataset[0];
            $remaining = array_slice($dataset, 1);
            $withoutFirst = self::combinations($remaining);
            foreach ($withoutFirst as $comb) {
                yield $comb;
                array_unshift($comb, $current);
                yield $comb;
            }
        }
    }
}